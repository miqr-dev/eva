<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'evaluation_campaign_id',
    'tan_code_hash',
    'started_at',
    'used_at',
    'expires_at',
    'is_active',
])]
class Tan extends Model
{
    /** @return BelongsTo<EvaluationCampaign, $this> */
    public function evaluationCampaign(): BelongsTo
    {
        return $this->belongsTo(EvaluationCampaign::class);
    }

    /** @return HasOne<SurveyResponse, $this> */
    public function response(): HasOne
    {
        return $this->hasOne(SurveyResponse::class);
    }

    /** @return HasMany<EmailRecipient, $this> */
    public function emailRecipients(): HasMany
    {
        return $this->hasMany(EmailRecipient::class);
    }

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
