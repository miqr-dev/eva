<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'scope_type',
    'organization_unit_id',
    'description',
    'is_active',
])]
class BenchmarkGroup extends Model
{
    /** @use HasFactory<\Database\Factories\BenchmarkGroupFactory> */
    use HasFactory;

    /** @return BelongsTo<OrganizationUnit, $this> */
    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    /** @return HasMany<BenchmarkSnapshot, $this> */
    public function snapshots(): HasMany
    {
        return $this->hasMany(BenchmarkSnapshot::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
