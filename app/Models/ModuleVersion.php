<?php

namespace App\Models;

use App\Enums\ModuleTargetType;
use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'module_id',
    'version_number',
    'title',
    'description',
    'status',
    'default_language',
    'target_type',
    'created_by_id',
    'published_at',
])]
class ModuleVersion extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleVersionFactory> */
    use HasFactory;

    /** @return BelongsTo<Module, $this> */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /** @return BelongsToMany<QuestionnaireVersion, $this> */
    public function questionnaireVersions(): BelongsToMany
    {
        return $this->belongsToMany(QuestionnaireVersion::class, 'questionnaire_version_modules')
            ->withPivot(['id', 'sort_order', 'repeat_mode'])
            ->withTimestamps();
    }

    /** @return HasMany<ModuleSection, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(ModuleSection::class)
            ->orderBy('sort_order');
    }

    protected function casts(): array
    {
        return [
            'version_number' => 'integer',
            'status' => PublicationStatus::class,
            'target_type' => ModuleTargetType::class,
            'published_at' => 'datetime',
        ];
    }
}
