<?php

namespace App\Http\Resources;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Course
 */
class CourseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_unit_id' => $this->organization_unit_id,
            'name' => $this->name,
            'code' => $this->code,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'is_active' => $this->is_active,
            'organization_unit' => new OrganizationUnitResource(
                $this->whenLoaded('organizationUnit'),
            ),
            'teachers' => TeacherResource::collection($this->whenLoaded('teachers')),
            'teacher_assignments' => $this->whenLoaded(
                'teachers',
                fn () => $this->teachers
                    ->map(fn (Teacher $teacher): array => [
                        'teacher_id' => $teacher->id,
                        'subject_id' => $teacher->pivot->subject_id,
                    ])
                    ->values(),
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
