<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'is_active', 'created_by_id'])]
class ReportTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\ReportTemplateFactory> */
    use HasFactory;

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /** @return HasMany<ReportTemplateSection, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(ReportTemplateSection::class)
            ->orderBy('sort_order');
    }

    /** @return HasMany<ReportRun, $this> */
    public function reportRuns(): HasMany
    {
        return $this->hasMany(ReportRun::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
