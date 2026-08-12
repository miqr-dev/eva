<?php

namespace App\Models;

use App\Enums\OrganizationUnitType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable(['parent_id', 'name', 'type', 'sort_order', 'is_active'])]
class OrganizationUnit extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationUnitFactory> */
    use HasFactory;

    /** @return BelongsTo<OrganizationUnit, $this> */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** @return HasMany<OrganizationUnit, $this> */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /** @return HasMany<User, $this> */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** @return HasMany<Course, $this> */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /** @return HasMany<Teacher, $this> */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
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

    /** @return HasMany<BenchmarkGroup, $this> */
    public function benchmarkGroups(): HasMany
    {
        return $this->hasMany(BenchmarkGroup::class);
    }

    protected function casts(): array
    {
        return [
            'type' => OrganizationUnitType::class,
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
