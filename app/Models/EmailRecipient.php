<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'email_job_id',
    'recipient_type',
    'recipient_email',
    'recipient_name',
    'user_id',
    'teacher_id',
    'tan_id',
])]
class EmailRecipient extends Model
{
    /** @return BelongsTo<EmailJob, $this> */
    public function emailJob(): BelongsTo
    {
        return $this->belongsTo(EmailJob::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Teacher, $this> */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /** @return BelongsTo<Tan, $this> */
    public function tan(): BelongsTo
    {
        return $this->belongsTo(Tan::class);
    }

    /** @return HasMany<EmailLog, $this> */
    public function logs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }
}
