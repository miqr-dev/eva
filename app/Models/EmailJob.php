<?php

namespace App\Models;

use App\Enums\EmailJobStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'evaluation_campaign_id',
    'email_template_id',
    'type',
    'scheduled_at',
    'status',
    'created_by_id',
])]
class EmailJob extends Model
{
    /** @return BelongsTo<EvaluationCampaign, $this> */
    public function evaluationCampaign(): BelongsTo
    {
        return $this->belongsTo(EvaluationCampaign::class);
    }

    /** @return BelongsTo<EmailTemplate, $this> */
    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /** @return HasMany<EmailRecipient, $this> */
    public function recipients(): HasMany
    {
        return $this->hasMany(EmailRecipient::class);
    }

    /** @return HasMany<EmailLog, $this> */
    public function logs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'status' => EmailJobStatus::class,
        ];
    }
}
