<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'evaluation_campaign_id',
    'target_type',
    'target_id',
    'label',
    'sort_order',
])]
class EvaluationCampaignTarget extends Model
{
    /** @return BelongsTo<EvaluationCampaign, $this> */
    public function evaluationCampaign(): BelongsTo
    {
        return $this->belongsTo(EvaluationCampaign::class);
    }

    /** @return MorphTo<Model, $this> */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return HasMany<ResponseAnswer, $this> */
    public function responseAnswers(): HasMany
    {
        return $this->hasMany(ResponseAnswer::class);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
