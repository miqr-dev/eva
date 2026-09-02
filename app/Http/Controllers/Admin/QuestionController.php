<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationStatus;
use App\Enums\QuestionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    public function store(StoreQuestionRequest $request): QuestionResource
    {
        $section = ModuleSection::query()->findOrFail(
            $request->integer('module_section_id'),
        );
        $this->ensureDraft($section->moduleVersion);
        $validated = $request->validated();

        $question = DB::transaction(function () use (
            $section,
            $validated,
        ): Question {
            $maximumSortOrder = $section->questions()->max('sort_order');
            $question = Question::query()->create([
                ...$this->questionAttributes($validated),
                'module_section_id' => $section->id,
                'sort_order' => $maximumSortOrder === null
                    ? 0
                    : ((int) $maximumSortOrder) + 1,
            ]);
            $this->syncOptions($question, $validated);

            return $question;
        });

        return new QuestionResource($question->load('options'));
    }

    public function update(
        UpdateQuestionRequest $request,
        Question $question,
    ): QuestionResource {
        $section = ModuleSection::query()->findOrFail(
            $request->integer('module_section_id'),
        );
        $this->ensureDraft($question->moduleSection->moduleVersion);
        $this->ensureDraft($section->moduleVersion);
        $validated = $request->validated();

        DB::transaction(function () use ($question, $section, $validated): void {
            $attributes = [
                ...$this->questionAttributes($validated),
                'module_section_id' => $section->id,
            ];

            if (! $question->moduleSection->is($section)) {
                $maximumSortOrder = $section->questions()->max('sort_order');
                $attributes['sort_order'] = $maximumSortOrder === null
                    ? 0
                    : ((int) $maximumSortOrder) + 1;
            }

            $question->update($attributes);
            $this->syncOptions($question, $validated);
        });

        return new QuestionResource($question->load('options'));
    }

    public function destroy(Question $question): Response
    {
        $this->authorizePermission('questionnaires.manage');
        $this->ensureDraft($question->moduleSection->moduleVersion);
        $question->delete();

        return response()->noContent();
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function questionAttributes(array $validated): array
    {
        $type = QuestionType::from($validated['question_type']);
        $isScale = $type === QuestionType::Scale;

        return [
            ...Arr::only($validated, [
                'question_text',
                'question_type',
                'is_required',
            ]),
            'scale_min' => $isScale ? $validated['scale_min'] : null,
            'scale_max' => $isScale ? $validated['scale_max'] : null,
            'scale_min_label' => $isScale
                ? ($validated['scale_min_label'] ?? null)
                : null,
            'scale_max_label' => $isScale
                ? ($validated['scale_max_label'] ?? null)
                : null,
            'scale_labels' => $isScale
                ? ($validated['scale_labels'] ?? null)
                : null,
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function syncOptions(Question $question, array $validated): void
    {
        $question->options()->delete();
        $type = QuestionType::from($validated['question_type']);

        if ($type !== QuestionType::SingleChoice) {
            return;
        }

        foreach ($validated['options'] as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'value' => (string) ($index + 1),
                'sort_order' => $index,
            ]);
        }
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
