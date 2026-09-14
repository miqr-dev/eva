<?php

namespace App\Services;

use App\Models\EvaluationCampaign;
use App\Models\EvaluationCampaignTarget;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EvaluationCampaignTargetSyncService
{
    /**
     * Keep the campaign's teacher targets in sync with the teacher/subject
     * assignments currently on its course. A teacher who is assigned to the
     * course for two different subjects gets one target per subject, so
     * they can be evaluated separately for each. Skipped once TANs exist,
     * so an already-launched evaluation's structure never shifts underneath
     * it.
     */
    public function syncCourseTeachers(EvaluationCampaign $campaign): void
    {
        if ($campaign->tans()->exists()) {
            return;
        }

        $assignments = $this->activeAssignments($campaign);

        $this->deleteStaleTargets($campaign, $assignments);
        $this->upsertTargets($campaign, $assignments);
    }

    /**
     * @return Collection<int, object{teacher_id: int, subject_id: int|null}>
     */
    private function activeAssignments(EvaluationCampaign $campaign): Collection
    {
        if ($campaign->course_id === null) {
            return collect();
        }

        return DB::table('course_teacher')
            ->join('teachers', 'teachers.id', '=', 'course_teacher.teacher_id')
            ->where('course_teacher.course_id', $campaign->course_id)
            ->where('teachers.is_active', true)
            ->orderBy('teachers.name')
            ->orderBy('course_teacher.subject_id')
            ->get(['course_teacher.teacher_id', 'course_teacher.subject_id'])
            ->map(fn (object $row): object => (object) [
                'teacher_id' => (int) $row->teacher_id,
                'subject_id' => $row->subject_id === null ? null : (int) $row->subject_id,
            ]);
    }

    /**
     * @param  Collection<int, object{teacher_id: int, subject_id: int|null}>  $assignments
     */
    private function deleteStaleTargets(EvaluationCampaign $campaign, Collection $assignments): void
    {
        $existingTargets = EvaluationCampaignTarget::query()
            ->where('evaluation_campaign_id', $campaign->id)
            ->where('target_type', 'teacher')
            ->get(['id', 'target_id', 'subject_id']);

        $staleIds = $existingTargets
            ->reject(function (EvaluationCampaignTarget $target) use ($assignments): bool {
                $targetId = (int) $target->target_id;
                $subjectId = $target->subject_id === null ? null : (int) $target->subject_id;

                return $assignments->contains(
                    fn (object $assignment): bool => $assignment->teacher_id === $targetId
                        && $assignment->subject_id === $subjectId,
                );
            })
            ->pluck('id');

        if ($staleIds->isNotEmpty()) {
            EvaluationCampaignTarget::query()->whereKey($staleIds)->delete();
        }
    }

    /**
     * @param  Collection<int, object{teacher_id: int, subject_id: int|null}>  $assignments
     */
    private function upsertTargets(EvaluationCampaign $campaign, Collection $assignments): void
    {
        $teacherNames = Teacher::query()
            ->whereIn('id', $assignments->pluck('teacher_id')->unique())
            ->pluck('name', 'id');

        $subjectNames = Subject::query()
            ->whereIn('id', $assignments->pluck('subject_id')->filter()->unique())
            ->pluck('name', 'id');

        foreach ($assignments->values() as $sortOrder => $assignment) {
            $label = $assignment->subject_id !== null && isset($subjectNames[$assignment->subject_id])
                ? "{$teacherNames[$assignment->teacher_id]} – {$subjectNames[$assignment->subject_id]}"
                : ($teacherNames[$assignment->teacher_id] ?? '');

            EvaluationCampaignTarget::query()->updateOrCreate(
                [
                    'evaluation_campaign_id' => $campaign->id,
                    'target_type' => 'teacher',
                    'target_id' => $assignment->teacher_id,
                    'subject_id' => $assignment->subject_id,
                ],
                [
                    'label' => $label,
                    'sort_order' => $sortOrder,
                ],
            );
        }
    }
}
