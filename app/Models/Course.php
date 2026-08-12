<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'organization_unit_id',
    'name',
    'code',
    'starts_at',
    'ends_at',
    'is_active',
])]
class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    /** @return BelongsTo<OrganizationUnit, $this> */
    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    /** @return BelongsToMany<Teacher, $this> */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)
            ->withTimestamps();
    }

    /** @return HasMany<EvaluationCampaign, $this> */
    public function evaluationCampaigns(): HasMany
    {
        return $this->hasMany(EvaluationCampaign::class);
    }

    /** @return MorphMany<EvaluationCampaignTarget, $this> */
    public function evaluationCampaignTargets(): MorphMany
    {
        return $this->morphMany(EvaluationCampaignTarget::class, 'target');
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
