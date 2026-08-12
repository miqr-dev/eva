<?php

namespace App\Models;

use App\Enums\EmailLogStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'email_job_id',
    'email_recipient_id',
    'recipient_email',
    'status',
    'error_message',
    'sent_at',
])]
class EmailLog extends Model
{
    /** @return BelongsTo<EmailJob, $this> */
    public function emailJob(): BelongsTo
    {
        return $this->belongsTo(EmailJob::class);
    }

    /** @return BelongsTo<EmailRecipient, $this> */
    public function emailRecipient(): BelongsTo
    {
        return $this->belongsTo(EmailRecipient::class);
    }

    protected function casts(): array
    {
        return [
            'status' => EmailLogStatus::class,
            'sent_at' => 'datetime',
        ];
    }
}
