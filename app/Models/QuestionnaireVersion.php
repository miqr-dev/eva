<?php

namespace App\Models;

use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'questionnaire_template_id',
    'version_number',
    'title',
    'description',
    'status',
    'default_language',
    'min_answers_to_show_results',
    'created_by_id',
    'published_at',
])]
class QuestionnaireVersion extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionnaireVersionFactory> */
    use HasFactory;

    /** @return BelongsTo<QuestionnaireTemplate, $this> */
    public function questionnaireTemplate(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireTemplate::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /** @return BelongsToMany<ModuleVersion, $this> */
    public function moduleVersions(): BelongsToMany
    {
        return $this->belongsToMany(ModuleVersion::class, 'questionnaire_version_modules')
            ->withPivot(['id', 'sort_order', 'repeat_mode'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /** @return HasMany<QuestionnaireVersionModule, $this> */
    public function moduleLinks(): HasMany
    {
        return $this->hasMany(QuestionnaireVersionModule::class)
            ->orderBy('sort_order');
    }

    /** @return HasMany<EvaluationCampaign, $this> */
    public function evaluationCampaigns(): HasMany
    {
        return $this->hasMany(EvaluationCampaign::class);
    }

    protected function casts(): array
    {
        return [
            'version_number' => 'integer',
            'status' => PublicationStatus::class,
            'min_answers_to_show_results' => 'integer',
            'published_at' => 'datetime',
        ];
    }
}
