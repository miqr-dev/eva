<?php

namespace App\Models;

use App\Enums\EvaluationCampaignStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organization_unit_id',
    'course_id',
    'questionnaire_version_id',
    'title',
    'description',
    'starts_at',
    'ends_at',
    'status',
    'min_answers_to_show_results',
    'created_by_id',
])]
class EvaluationCampaign extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationCampaignFactory> */
    use HasFactory;

    /** @return BelongsTo<OrganizationUnit, $this> */
    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    /** @return BelongsTo<Course, $this> */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** @return BelongsTo<QuestionnaireVersion, $this> */
    public function questionnaireVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireVersion::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /** @return HasMany<EvaluationCampaignTarget, $this> */
    public function targets(): HasMany
    {
        return $this->hasMany(EvaluationCampaignTarget::class)
            ->orderBy('sort_order');
    }

    /** @return HasMany<Tan, $this> */
    public function tans(): HasMany
    {
        return $this->hasMany(Tan::class);
    }

    /** @return HasMany<SurveyResponse, $this> */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /** @return HasMany<ReportRun, $this> */
    public function reportRuns(): HasMany
    {
        return $this->hasMany(ReportRun::class);
    }

    /** @return HasMany<EmailJob, $this> */
    public function emailJobs(): HasMany
    {
        return $this->hasMany(EmailJob::class);
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => EvaluationCampaignStatus::class,
            'min_answers_to_show_results' => 'integer',
        ];
    }
}
