<?php

namespace App\Models;

use App\Enums\ReportRunStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'evaluation_campaign_id',
    'report_template_id',
    'status',
    'file_path',
    'generated_at',
    'created_by_id',
])]
class ReportRun extends Model
{
    /** @return BelongsTo<EvaluationCampaign, $this> */
    public function evaluationCampaign(): BelongsTo
    {
        return $this->belongsTo(EvaluationCampaign::class);
    }

    /** @return BelongsTo<ReportTemplate, $this> */
    public function reportTemplate(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected function casts(): array
    {
        return [
            'status' => ReportRunStatus::class,
            'generated_at' => 'datetime',
        ];
    }
}
