<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\QuestionnaireTemplate
 */
class QuestionnaireTemplateResource extends JsonResource
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
            'versions' => $this->whenLoaded(
                'versions',
                fn () => $this->versions->map->only([
                    'id',
                    'version_number',
                    'title',
                    'status',
                    'published_at',
                ]),
            ),
            'versions_count' => $this->whenCounted('versions'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
