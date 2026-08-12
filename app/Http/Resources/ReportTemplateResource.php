<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ReportTemplate
 */
class ReportTemplateResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_by_id' => $this->created_by_id,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'sections' => $this->whenLoaded(
                'sections',
                fn () => $this->sections->map->only([
                    'id',
                    'title',
                    'section_type',
                    'config',
                    'sort_order',
                ]),
            ),
            'sections_count' => $this->whenCounted('sections'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
