<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionnaireVersionRequest;
use App\Http\Requests\UpdateQuestionnaireVersionRequest;
use App\Http\Resources\QuestionnaireBuilderVersionResource;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\QuestionnaireVersionModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuestionnaireVersionController extends Controller
{
    public function store(
        StoreQuestionnaireVersionRequest $request,
    ): QuestionnaireBuilderVersionResource {
        $template = QuestionnaireTemplate::query()
            ->findOrFail($request->integer('questionnaire_template_id'));
        $sourceVersionId = $request->integer('source_version_id');
        $source = $sourceVersionId > 0
            ? QuestionnaireVersion::query()
                ->whereBelongsTo($template)
                ->with('moduleLinks')
                ->findOrFail($sourceVersionId)
            : null;

        $version = DB::transaction(function () use (
            $request,
            $source,
            $template,
        ): QuestionnaireVersion {
            $sourceAttributes = $source instanceof QuestionnaireVersion
                ? [
                    'title' => $source->title,
                    'description' => $source->description,
                    'default_language' => $source->default_language,
                    'min_answers_to_show_results' => $source->min_answers_to_show_results,
                ]
                : [
                    'title' => $template->name,
                    'description' => $template->description,
                    'default_language' => 'de',
                    'min_answers_to_show_results' => 5,
                ];

            $version = QuestionnaireVersion::query()->create([
                'questionnaire_template_id' => $template->id,
                'version_number' => ((int) $template->versions()->max('version_number')) + 1,
                'title' => $request->string('title')->toString() ?: $sourceAttributes['title'],
                'description' => $request->input('description', $sourceAttributes['description']),
                'status' => PublicationStatus::Draft,
                'default_language' => $request->string('default_language')->toString()
                    ?: $sourceAttributes['default_language'],
                'min_answers_to_show_results' => $request->integer(
                    'min_answers_to_show_results',
                    $sourceAttributes['min_answers_to_show_results'],
                ),
                'created_by_id' => $request->user()->id,
                'published_at' => null,
            ]);

            if ($source instanceof QuestionnaireVersion) {
                foreach ($source->moduleLinks as $moduleLink) {
                    QuestionnaireVersionModule::query()->create([
                        'questionnaire_version_id' => $version->id,
                        'module_version_id' => $moduleLink->module_version_id,
                        'sort_order' => $moduleLink->sort_order,
                        'repeat_mode' => $moduleLink->getRawOriginal('repeat_mode'),
                    ]);
                }
            }

            return $version;
        });

        return new QuestionnaireBuilderVersionResource(
            $version->load(['moduleLinks.moduleVersion.module', 'moduleLinks.moduleVersion.targetRole']),
        );
    }

    public function update(
        UpdateQuestionnaireVersionRequest $request,
        QuestionnaireVersion $questionnaireVersion,
    ): QuestionnaireBuilderVersionResource {
        $this->ensureDraft($questionnaireVersion);

        $questionnaireVersion->update($request->validated());

        return new QuestionnaireBuilderVersionResource(
            $questionnaireVersion->load(['moduleLinks.moduleVersion.module', 'moduleLinks.moduleVersion.targetRole']),
        );
    }

    public function publish(
        Request $request,
        QuestionnaireVersion $questionnaireVersion,
    ): QuestionnaireBuilderVersionResource {
        abort_unless(
            $request->user()?->hasPermission('questionnaires.manage') ?? false,
            403,
        );
        $this->ensureDraft($questionnaireVersion);

        if ($questionnaireVersion->moduleLinks()->count() === 0) {
            throw ValidationException::withMessages([
                'modules' => 'Eine Fragebogenversion benötigt mindestens ein Modul.',
            ]);
        }

        $questionnaireVersion->update([
            'status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);

        return new QuestionnaireBuilderVersionResource(
            $questionnaireVersion->load(['moduleLinks.moduleVersion.module', 'moduleLinks.moduleVersion.targetRole']),
        );
    }

    private function ensureDraft(QuestionnaireVersion $questionnaireVersion): void
    {
        if ($questionnaireVersion->getRawOriginal('status') !== PublicationStatus::Draft->value) {
            throw ValidationException::withMessages([
                'questionnaire_version_id' => 'Veröffentlichte Fragebogenversionen können nicht geändert werden.',
            ]);
        }
    }
}
