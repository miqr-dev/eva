<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class CourseTeacherAssignmentSyncService
{
    /**
     * Replace all of this teacher's course assignments with the given set.
     *
     * A plain belongsToMany sync() can't represent the same course appearing
     * twice with a different subject each time, so this replaces the pivot
     * rows directly instead.
     *
     * @param  array<int, array{course_id: int, subject_id: int|null}>  $assignments
     */
    public function syncForTeacher(Teacher $teacher, array $assignments): void
    {
        DB::table('course_teacher')->where('teacher_id', $teacher->id)->delete();

        $this->insert($assignments, ['teacher_id' => $teacher->id], 'course_id');
    }

    /**
     * Replace all of this course's teacher assignments with the given set.
     *
     * @param  array<int, array{teacher_id: int, subject_id: int|null}>  $assignments
     */
    public function syncForCourse(Course $course, array $assignments): void
    {
        DB::table('course_teacher')->where('course_id', $course->id)->delete();

        $this->insert($assignments, ['course_id' => $course->id], 'teacher_id');
    }

    /**
     * @param  array<int, array<string, mixed>>  $assignments
     * @param  array<string, int>  $fixed
     */
    private function insert(array $assignments, array $fixed, string $otherKey): void
    {
        if ($assignments === []) {
            return;
        }

        $now = now();

        $rows = collect($assignments)
            ->map(fn (array $assignment): array => [
                ...$fixed,
                $otherKey => $assignment[$otherKey],
                'subject_id' => $assignment['subject_id'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        DB::table('course_teacher')->insert($rows);
    }
}
