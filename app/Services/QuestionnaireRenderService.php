<?php

namespace App\Services;

use App\Enums\RepeatMode;
use App\Models\EvaluationCampaign;
use App\Models\EvaluationCampaignTarget;
use App\Models\ModuleSection;
use App\Models\Question;
use App\Models\QuestionnaireVersionModule;

class QuestionnaireRenderService
{
    /**
     * @return array<string, mixed>
     */
    public function render(EvaluationCampaign $campaign): array
    {
        $campaign->loadMissing([
            'questionnaireVersion.moduleLinks.moduleVersion.module',
            'questionnaireVersion.moduleLinks.moduleVersion.sections.questions.options',
            'targets',
        ]);

        return [
            'campaign' => [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'description' => $campaign->description,
                'starts_at' => $campaign->starts_at,
                'ends_at' => $campaign->ends_at,
            ],
            'questionnaire' => [
                'id' => $campaign->questionnaireVersion->id,
                'title' => $campaign->questionnaireVersion->title,
                'version_number' => $campaign->questionnaireVersion->version_number,
                'default_language' => $campaign->questionnaireVersion->default_language,
            ],
            'modules' => $campaign->questionnaireVersion
                ->moduleLinks
                ->flatMap(fn (QuestionnaireVersionModule $moduleLink): array => $this->renderModuleLink(
                    $moduleLink,
                    $campaign,
                ))
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function renderModuleLink(
        QuestionnaireVersionModule $moduleLink,
        EvaluationCampaign $campaign,
    ): array {
        if ($moduleLink->getRawOriginal('repeat_mode') === RepeatMode::Once->value) {
            return [$this->renderModule($moduleLink)];
        }

        $targetType = $moduleLink->moduleVersion->getRawOriginal('target_type');

        if ($targetType === 'none') {
            return [$this->renderModule($moduleLink)];
        }

        return $campaign->targets
            ->where('target_type', $targetType)
            ->map(fn (EvaluationCampaignTarget $target): array => $this->renderModule(
                $moduleLink,
                $target,
            ))
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function renderModule(
        QuestionnaireVersionModule $moduleLink,
        ?EvaluationCampaignTarget $target = null,
    ): array {
        $moduleVersion = $moduleLink->moduleVersion;

        return [
            'id' => $moduleLink->id,
            'module_version_id' => $moduleVersion->id,
            'title' => $moduleVersion->module->name ?? $moduleVersion->title,
            'version_title' => $moduleVersion->title,
            'repeat_mode' => $moduleLink->getRawOriginal('repeat_mode'),
            'target_type' => $moduleVersion->getRawOriginal('target_type'),
            'target' => $target instanceof EvaluationCampaignTarget
                ? [
                    'id' => $target->id,
                    'type' => $target->target_type,
                    'label' => $target->label,
                ]
                : null,
            'sections' => $moduleVersion->sections
                ->map(fn (ModuleSection $section): array => [
                    'id' => $section->id,
                    'title' => $section->title,
                    'description' => $section->description,
                    'questions' => $section->questions
                        ->map(fn (Question $question): array => $this->renderQuestion(
                            $question,
                            $target,
                        ))
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function renderQuestion(
        Question $question,
        ?EvaluationCampaignTarget $target,
    ): array {
        return [
            'id' => $question->id,
            'answer_key' => $this->answerKey($question, $target),
            'question_text' => $question->question_text,
            'question_type' => $question->getRawOriginal('question_type'),
            'scale_min' => $question->scale_min,
            'scale_max' => $question->scale_max,
            'scale_min_label' => $question->scale_min_label,
            'scale_max_label' => $question->scale_max_label,
            'is_required' => $question->is_required,
            'target_id' => $target?->id,
            'options' => $question->options
                ->map(fn ($option): array => [
                    'id' => $option->id,
                    'label' => $option->option_text,
                    'value' => $option->value,
                ])
                ->values()
                ->all(),
        ];
    }

    private function answerKey(
        Question $question,
        ?EvaluationCampaignTarget $target,
    ): string {
        return $target instanceof EvaluationCampaignTarget
            ? "q{$question->id}_t{$target->id}"
            : "q{$question->id}";
    }
}
