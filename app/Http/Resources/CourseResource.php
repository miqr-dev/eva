<?php

namespace App\Http\Resources;

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
