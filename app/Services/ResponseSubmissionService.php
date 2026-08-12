<?php

namespace App\Services;

use App\Models\SurveyResponse;
use App\Models\Tan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ResponseSubmissionService
{
    public function __construct(
        private QuestionnaireRenderService $questionnaireRenderService,
        private TanService $tanService,
    ) {}

    /**
     * @param  array<string, mixed>  $answers
     */
    public function submit(
        Tan $tan,
        array $answers,
        string $language,
        ?string $userAgent,
        ?string $ipAddress,
    ): SurveyResponse {
        return DB::transaction(function () use (
            $answers,
            $ipAddress,
            $language,
            $tan,
            $userAgent,
        ): SurveyResponse {
            $lockedTan = Tan::query()
                ->whereKey($tan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->tanService->ensureCanBeUsed($lockedTan);
            $campaign = $lockedTan->evaluationCampaign()
                ->with([
                    'questionnaireVersion.moduleLinks.moduleVersion.module',
                    'questionnaireVersion.moduleLinks.moduleVersion.sections.questions.options',
                    'targets',
                ])
                ->firstOrFail();
            $renderedForm = $this->questionnaireRenderService->render($campaign);

            $response = SurveyResponse::query()->create([
                'evaluation_campaign_id' => $campaign->id,
                'tan_id' => $lockedTan->id,
                'submitted_at' => now(),
                'language' => $language,
                'user_agent' => $userAgent,
                'ip_hash' => $ipAddress === null
                    ? null
                    : hash('sha256', $ipAddress.'|'.config('app.key')),
            ]);

            foreach ($this->answerAttributes($renderedForm, $answers) as $attributes) {
                $response->answers()->create($attributes);
            }

            $lockedTan->update(['used_at' => now()]);

            return $response;
        });
    }

    /**
     * @param  array<string, mixed>  $renderedForm
     * @param  array<string, mixed>  $answers
     * @return array<int, array<string, mixed>>
     */
    private function answerAttributes(array $renderedForm, array $answers): array
    {
        $attributes = [];

        foreach ($renderedForm['modules'] as $module) {
            foreach ($module['sections'] as $section) {
                foreach ($section['questions'] as $question) {
                    $answerKey = $question['answer_key'];
                    $value = $answers[$answerKey] ?? null;

                    if (! $this->hasAnswer($value)) {
                        if ($question['is_required']) {
                            throw ValidationException::withMessages([
                                "answers.{$answerKey}" => 'Diese Frage muss beantwortet werden.',
                            ]);
                        }

                        continue;
                    }

                    $attributes[] = [
                        'question_id' => $question['id'],
                        'evaluation_campaign_target_id' => $question['target_id'],
                        ...$this->typedAnswerAttributes($question, $value),
                    ];
                }
            }
        }

        return $attributes;
    }

    private function hasAnswer(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $question
     * @return array<string, mixed>
     */
    private function typedAnswerAttributes(array $question, mixed $value): array
    {
        return match ($question['question_type']) {
            'scale' => $this->scaleAnswerAttributes($question, $value),
            'free_text' => ['text_value' => trim((string) $value)],
            'yes_no' => ['boolean_value' => $this->booleanValue($question, $value)],
            'single_choice' => $this->singleChoiceAnswerAttributes($question, $value),
            default => throw ValidationException::withMessages([
                "answers.{$question['answer_key']}" => 'Dieser Fragetyp wird nicht unterstützt.',
            ]),
        };
    }

    /**
     * @param  array<string, mixed>  $question
     * @return array<string, mixed>
     */
    private function scaleAnswerAttributes(array $question, mixed $value): array
    {
        if (! is_numeric($value)) {
            throw ValidationException::withMessages([
                "answers.{$question['answer_key']}" => 'Bitte geben Sie einen Zahlenwert an.',
            ]);
        }

        $numericValue = (float) $value;
        $minimum = (float) $question['scale_min'];
        $maximum = (float) $question['scale_max'];

        if ($numericValue < $minimum || $numericValue > $maximum) {
            throw ValidationException::withMessages([
                "answers.{$question['answer_key']}" => 'Der Wert liegt außerhalb der Skala.',
            ]);
        }

        return ['numeric_value' => $numericValue];
    }

    /**
     * @param  array<string, mixed>  $question
     */
    private function booleanValue(array $question, mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = mb_strtolower((string) $value);

        return match ($normalized) {
            '1', 'true', 'yes', 'ja' => true,
            '0', 'false', 'no', 'nein' => false,
            default => throw ValidationException::withMessages([
                "answers.{$question['answer_key']}" => 'Bitte wählen Sie Ja oder Nein.',
            ]),
        };
    }

    /**
     * @param  array<string, mixed>  $question
     * @return array<string, mixed>
     */
    private function singleChoiceAnswerAttributes(
        array $question,
        mixed $value,
    ): array {
        $optionId = (int) $value;
        $validOptionIds = [];

        foreach ($question['options'] as $option) {
            if (is_array($option) && isset($option['id'])) {
                $validOptionIds[] = (int) $option['id'];
            }
        }

        if (! in_array($optionId, $validOptionIds, true)) {
            throw ValidationException::withMessages([
                "answers.{$question['answer_key']}" => 'Bitte wählen Sie eine gültige Antwortoption.',
            ]);
        }

        return ['question_option_id' => $optionId];
    }
}
