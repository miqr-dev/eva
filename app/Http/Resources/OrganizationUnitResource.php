<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\OrganizationUnit
 */
class OrganizationUnitResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'type' => $this->type,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'parent' => new self($this->whenLoaded('parent')),
            'children' => self::collection($this->whenLoaded('children')),
            'children_count' => $this->whenCounted('children'),
            'users_count' => $this->whenCounted('users'),
            'courses_count' => $this->whenCounted('courses'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
