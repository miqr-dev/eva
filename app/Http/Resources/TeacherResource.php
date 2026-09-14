<?php

namespace App\Http\Resources;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Teacher
 */
class TeacherResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'organization_unit_id' => $this->organization_unit_id,
            'name' => $this->name,
            'teacher_role_id' => $this->teacher_role_id,
            'teacher_role' => new TeacherRoleResource(
                $this->whenLoaded('teacherRole'),
            ),
            'email' => $this->email,
            'is_active' => $this->is_active,
            'is_remote' => $this->is_remote,
            'organization_unit' => new OrganizationUnitResource(
                $this->whenLoaded('organizationUnit'),
            ),
            'user' => new UserResource($this->whenLoaded('user')),
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'course_assignments' => $this->whenLoaded(
                'courses',
                fn () => $this->courses
                    ->map(fn (Course $course): array => [
                        'course_id' => $course->id,
                        'subject_id' => $course->pivot->subject_id,
                    ])
                    ->values(),
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
