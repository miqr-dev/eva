<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'user_id',
    'organization_unit_id',
    'name',
    'email',
    'is_active',
])]
class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<OrganizationUnit, $this> */
    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    /** @return BelongsToMany<Course, $this> */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
            ->withTimestamps();
    }

    /** @return MorphMany<EvaluationCampaignTarget, $this> */
    public function evaluationCampaignTargets(): MorphMany
    {
        return $this->morphMany(EvaluationCampaignTarget::class, 'target');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
