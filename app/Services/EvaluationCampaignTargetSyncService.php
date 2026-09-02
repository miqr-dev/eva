<?php

namespace App\Services;

use App\Models\EvaluationCampaign;
use App\Models\EvaluationCampaignTarget;
use App\Models\Teacher;

class EvaluationCampaignTargetSyncService
{
    /**
     * Keep the campaign's teacher targets in sync with the teachers
     * currently assigned to its course. Skipped once TANs exist, so an
     * already-launched evaluation's structure never shifts underneath it.
     */
    public function syncCourseTeachers(EvaluationCampaign $campaign): void
    {
        if ($campaign->tans()->exists()) {
            return;
        }

        $teachers = $campaign->course_id === null
            ? collect()
            : Teacher::query()
                ->whereHas(
                    'courses',
                    fn ($query) => $query->where('courses.id', $campaign->course_id),
                )
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

        EvaluationCampaignTarget::query()
            ->where('evaluation_campaign_id', $campaign->id)
            ->where('target_type', 'teacher')
            ->whereNotIn('target_id', $teachers->pluck('id'))
            ->delete();

        foreach ($teachers as $sortOrder => $teacher) {
            EvaluationCampaignTarget::query()->updateOrCreate(
                [
                    'evaluation_campaign_id' => $campaign->id,
                    'target_type' => 'teacher',
                    'target_id' => $teacher->id,
                ],
                [
                    'label' => $teacher->name,
                    'sort_order' => $sortOrder,
                ],
            );
        }
    }
}
