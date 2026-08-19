<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'response_id',
    'question_id',
    'evaluation_campaign_target_id',
    'question_option_id',
    'numeric_value',
    'text_value',
    'boolean_value',
])]
class ResponseAnswer extends Model
{
    /** @return BelongsTo<SurveyResponse, $this> */
    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    /** @return BelongsTo<Question, $this> */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /** @return BelongsTo<EvaluationCampaignTarget, $this> */
    public function evaluationCampaignTarget(): BelongsTo
    {
        return $this->belongsTo(EvaluationCampaignTarget::class);
    }

    /** @return BelongsTo<QuestionOption, $this> */
    public function questionOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }

    protected function casts(): array
    {
        return [
            'numeric_value' => 'decimal:4',
            'boolean_value' => 'boolean',
        ];
    }
}
