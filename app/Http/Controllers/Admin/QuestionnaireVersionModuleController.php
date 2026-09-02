<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionnaireVersionModuleRequest;
use App\Http\Requests\UpdateQuestionnaireVersionModuleRequest;
use App\Http\Resources\QuestionnaireBuilderModuleLinkResource;
use App\Models\ModuleVersion;
use App\Models\QuestionnaireVersion;
use App\Models\QuestionnaireVersionModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class QuestionnaireVersionModuleController extends Controller
{
    public function store(
        StoreQuestionnaireVersionModuleRequest $request,
    ): QuestionnaireBuilderModuleLinkResource {
        $questionnaireVersion = QuestionnaireVersion::query()
            ->findOrFail($request->integer('questionnaire_version_id'));
        $this->ensureDraft($questionnaireVersion);

        $moduleVersion = ModuleVersion::query()
            ->findOrFail($request->integer('module_version_id'));

        if ($moduleVersion->getRawOriginal('status') !== PublicationStatus::Published->value) {
            throw ValidationException::withMessages([
                'module_version_id' => 'Nur veröffentlichte Module können verwendet werden.',
            ]);
        }

        if (
            $questionnaireVersion->moduleLinks()
                ->where('module_version_id', $moduleVersion->id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'module_version_id' => 'Dieses Modul ist bereits im Fragebogen enthalten.',
            ]);
        }

        $link = DB::transaction(function () use (
            $moduleVersion,
            $questionnaireVersion,
            $request,
        ): QuestionnaireVersionModule {
            $link = QuestionnaireVersionModule::query()->create([
                'questionnaire_version_id' => $questionnaireVersion->id,
                'module_version_id' => $moduleVersion->id,
                'sort_order' => 1000,
                'repeat_mode' => $request->string('repeat_mode')->toString(),
            ]);

            $this->reorderLinks(
                $questionnaireVersion,
                $link,
                $request->integer(
                    'sort_order',
                    (int) $questionnaireVersion->moduleLinks()->count() - 1,
                ),
            );

            return $link;
        });

        return new QuestionnaireBuilderModuleLinkResource(
            $link->load(['moduleVersion.module', 'moduleVersion.targetRole']),
        );
    }

    public function update(
        UpdateQuestionnaireVersionModuleRequest $request,
        QuestionnaireVersionModule $questionnaireVersionModule,
    ): QuestionnaireBuilderModuleLinkResource {
        $questionnaireVersion = $questionnaireVersionModule
            ->questionnaireVersion()
            ->firstOrFail();
        $this->ensureDraft($questionnaireVersion);

        DB::transaction(function () use (
            $questionnaireVersion,
            $questionnaireVersionModule,
            $request,
        ): void {
            $questionnaireVersionModule->update([
                'repeat_mode' => $request->string('repeat_mode')->toString(),
            ]);

            $this->reorderLinks(
                $questionnaireVersion,
                $questionnaireVersionModule,
                $request->integer('sort_order'),
            );
        });

        return new QuestionnaireBuilderModuleLinkResource(
            $questionnaireVersionModule->refresh()->load(['moduleVersion.module', 'moduleVersion.targetRole']),
        );
    }

    public function destroy(
        QuestionnaireVersionModule $questionnaireVersionModule,
    ): Response {
        $this->authorizePermission('questionnaires.manage');

        $questionnaireVersion = $questionnaireVersionModule
            ->questionnaireVersion()
            ->firstOrFail();
        $this->ensureDraft($questionnaireVersion);

        DB::transaction(function () use (
            $questionnaireVersion,
            $questionnaireVersionModule,
        ): void {
            $questionnaireVersionModule->delete();
            $this->reorderLinks($questionnaireVersion);
        });

        return response()->noContent();
    }

    private function ensureDraft(QuestionnaireVersion $questionnaireVersion): void
    {
        if ($questionnaireVersion->getRawOriginal('status') !== PublicationStatus::Draft->value) {
            throw ValidationException::withMessages([
                'questionnaire_version_id' => 'Veröffentlichte Fragebogenversionen können nicht geändert werden.',
            ]);
        }
    }

    private function reorderLinks(
        QuestionnaireVersion $questionnaireVersion,
        ?QuestionnaireVersionModule $movingLink = null,
        ?int $targetSortOrder = null,
    ): void {
        /** @var Collection<int, QuestionnaireVersionModule> $links */
        $links = QuestionnaireVersionModule::query()
            ->where('questionnaire_version_id', $questionnaireVersion->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($movingLink instanceof QuestionnaireVersionModule && $targetSortOrder !== null) {
            $links = $links
                ->reject(fn (QuestionnaireVersionModule $link): bool => $link->id === $movingLink->id)
                ->values();
            $targetIndex = max(0, min($targetSortOrder, $links->count()));
            $links->splice($targetIndex, 0, [$movingLink]);
            $links = $links->values();
        }

        QuestionnaireVersionModule::query()
            ->where('questionnaire_version_id', $questionnaireVersion->id)
            ->update([
                'sort_order' => DB::raw('sort_order + 100000'),
            ]);

        foreach ($links->values() as $index => $link) {
            $link->update(['sort_order' => $index]);
        }
    }
}
