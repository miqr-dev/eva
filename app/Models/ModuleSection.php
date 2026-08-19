<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['module_version_id', 'title', 'description', 'sort_order'])]
class ModuleSection extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleSectionFactory> */
    use HasFactory;

    /** @return BelongsTo<ModuleVersion, $this> */
    public function moduleVersion(): BelongsTo
    {
        return $this->belongsTo(ModuleVersion::class);
    }

    /** @return HasMany<Question, $this> */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)
            ->orderBy('sort_order');
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
