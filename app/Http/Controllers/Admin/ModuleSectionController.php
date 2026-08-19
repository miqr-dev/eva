<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuleSectionRequest;
use App\Http\Requests\UpdateModuleSectionRequest;
use App\Http\Resources\ModuleSectionResource;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ModuleSectionController extends Controller
{
    public function store(StoreModuleSectionRequest $request): ModuleSectionResource
    {
        $moduleVersion = ModuleVersion::query()->findOrFail(
            $request->integer('module_version_id'),
        );
        $this->ensureDraft($moduleVersion);
        $maximumSortOrder = $moduleVersion->sections()->max('sort_order');

        $section = ModuleSection::query()->create([
            ...$request->validated(),
            'sort_order' => $maximumSortOrder === null
                ? 0
                : ((int) $maximumSortOrder) + 1,
        ]);

        return new ModuleSectionResource($section->load('questions.options'));
    }

    public function update(
        UpdateModuleSectionRequest $request,
        ModuleSection $moduleSection,
    ): ModuleSectionResource {
        $this->ensureDraft($moduleSection->moduleVersion);
        $moduleSection->update($request->validated());

        return new ModuleSectionResource(
            $moduleSection->load('questions.options'),
        );
    }

    public function destroy(ModuleSection $moduleSection): Response
    {
        $this->authorizePermission('questionnaires.manage');
        $this->ensureDraft($moduleSection->moduleVersion);
        $moduleSection->delete();

        return response()->noContent();
    }

    private function ensureDraft(ModuleVersion $moduleVersion): void
    {
        if ($moduleVersion->getRawOriginal('status') !== PublicationStatus::Draft->value) {
            throw ValidationException::withMessages([
                'module_version_id' => 'Veröffentlichte Modulversionen können nicht bearbeitet werden.',
            ]);
        }
    }
}
