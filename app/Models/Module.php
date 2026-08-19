<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'is_active', 'created_by_id'])]
class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /** @return HasMany<ModuleVersion, $this> */
    public function versions(): HasMany
    {
        return $this->hasMany(ModuleVersion::class)
            ->orderByDesc('version_number');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
