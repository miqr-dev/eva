<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'type', 'subject', 'body', 'is_active'])]
class EmailTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\EmailTemplateFactory> */
    use HasFactory;

    /** @return HasMany<EmailJob, $this> */
    public function emailJobs(): HasMany
    {
        return $this->hasMany(EmailJob::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
