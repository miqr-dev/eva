<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'evaluation_campaign_id',
    'tan_id',
    'submitted_at',
    'language',
    'user_agent',
    'ip_hash',
])]
class SurveyResponse extends Model
{
    protected $table = 'responses';

    /** @return BelongsTo<EvaluationCampaign, $this> */
    public function evaluationCampaign(): BelongsTo
    {
        return $this->belongsTo(EvaluationCampaign::class);
    }

    /** @return BelongsTo<Tan, $this> */
    public function tan(): BelongsTo
    {
        return $this->belongsTo(Tan::class);
    }

    /** @return HasMany<ResponseAnswer, $this> */
    public function answers(): HasMany
    {
        return $this->hasMany(ResponseAnswer::class, 'response_id');
    }

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }
}
