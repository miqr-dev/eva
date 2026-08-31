<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuleVersionRequest;
use App\Http\Requests\UpdateModuleVersionRequest;
use App\Http\Resources\ModuleVersionEditorResource;
use App\Models\Module;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ModuleVersionController extends Controller
{
    public function store(
        StoreModuleVersionRequest $request,
    ): ModuleVersionEditorResource {
        $module = Module::query()->findOrFail($request->integer('module_id'));
        $sourceVersionId = $request->integer('source_version_id');
        $source = $sourceVersionId > 0
            ? ModuleVersion::query()
                ->with('sections.questions.options')
                ->findOrFail($sourceVersionId)
            : null;

        $moduleVersion = DB::transaction(function () use (
            $module,
            $request,
            $source,
        ): ModuleVersion {
            $sourceAttributes = $source instanceof ModuleVersion
                ? [
                    'title' => $source->title,
                    'description' => $source->description,
                    'default_language' => $source->default_language,
                    'target_type' => $source->getRawOriginal('target_type'),
                ]
                : [
                    'title' => $module->name,
                    'description' => $module->description,
                    'default_language' => 'de',
                    'target_type' => 'none',
                ];
            $version = ModuleVersion::query()->create([
                'module_id' => $module->id,
                'version_number' => ((int) $module->versions()->max('version_number')) + 1,
                'title' => $sourceAttributes['title'],
                'description' => $sourceAttributes['description'],
                'status' => PublicationStatus::Draft,
                'default_language' => $sourceAttributes['default_language'],
                'target_type' => $request->input('target_type') ?? $sourceAttributes['target_type'],
                'created_by_id' => $request->user()->id,
                'published_at' => null,
            ]);

            if ($source instanceof ModuleVersion) {
                $this->copySections($source, $version);
            }

            return $version;
        });

        return new ModuleVersionEditorResource(
            $moduleVersion->load('sections.questions.options'),
        );
    }

    public function update(
        UpdateModuleVersionRequest $request,
        ModuleVersion $moduleVersion,
    ): ModuleVersionEditorResource {
        if ($moduleVersion->getRawOriginal('status') !== PublicationStatus::Draft->value) {
            throw ValidationException::withMessages([
                'module_version_id' => 'Nur Entwurfsversionen können geändert werden.',
            ]);
        }

        $moduleVersion->update($request->validated());

        return new ModuleVersionEditorResource(
            $moduleVersion->load('sections.questions.options'),
        );
    }

    public function publish(
        Request $request,
        ModuleVersion $moduleVersion,
    ): ModuleVersionEditorResource {
        abort_unless(
            $request->user()?->hasPermission('questionnaires.manage') ?? false,
            403,
        );

        if ($moduleVersion->getRawOriginal('status') !== PublicationStatus::Draft->value) {
            throw ValidationException::withMessages([
                'module_version_id' => 'Nur Entwurfsversionen können veröffentlicht werden.',
            ]);
        }

        if (! $moduleVersion->sections()->whereHas('questions')->exists()) {
            throw ValidationException::withMessages([
                'questions' => 'Eine Modulversion benötigt mindestens eine Frage.',
            ]);
        }

        $moduleVersion->update([
            'status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);

        return new ModuleVersionEditorResource(
            $moduleVersion->load('sections.questions.options'),
        );
    }

    private function copySections(
        ModuleVersion $source,
        ModuleVersion $destination,
    ): void {
        foreach ($source->sections as $sourceSection) {
            $section = ModuleSection::query()->create([
                'module_version_id' => $destination->id,
                'title' => $sourceSection->title,
                'description' => $sourceSection->description,
                'sort_order' => $sourceSection->sort_order,
            ]);

            foreach ($sourceSection->questions as $sourceQuestion) {
                $question = Question::query()->create([
                    'module_section_id' => $section->id,
                    'question_text' => $sourceQuestion->question_text,
                    'question_type' => $sourceQuestion->question_type,
                    'scale_min' => $sourceQuestion->scale_min,
                    'scale_max' => $sourceQuestion->scale_max,
                    'scale_min_label' => $sourceQuestion->scale_min_label,
                    'scale_max_label' => $sourceQuestion->scale_max_label,
                    'is_required' => $sourceQuestion->is_required,
                    'sort_order' => $sourceQuestion->sort_order,
                ]);

                foreach ($sourceQuestion->options as $sourceOption) {
                    QuestionOption::query()->create([
                        'question_id' => $question->id,
                        'option_text' => $sourceOption->option_text,
                        'value' => $sourceOption->value,
                        'sort_order' => $sourceOption->sort_order,
                    ]);
                }
            }
        }
    }
}
