<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_active' => $this->is_active,
            'organization_unit' => new OrganizationUnitResource(
                $this->whenLoaded('organizationUnit'),
            ),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'roles' => $this->whenLoaded(
                'roles',
                fn () => $this->roles->map->only(['id', 'name']),
            ),
            'permissions' => $this->whenLoaded(
                'permissions',
                fn () => $this->permissions->map->only(['id', 'name']),
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
