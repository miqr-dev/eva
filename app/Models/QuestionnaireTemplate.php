<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'is_active', 'created_by_id'])]
class QuestionnaireTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionnaireTemplateFactory> */
    use HasFactory;

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /** @return HasMany<QuestionnaireVersion, $this> */
    public function versions(): HasMany
    {
        return $this->hasMany(QuestionnaireVersion::class)
            ->orderByDesc('version_number');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
