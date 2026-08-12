<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\QuestionnaireTemplate
 */
class QuestionnaireBuilderTemplateResource extends JsonResource
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
            'versions' => QuestionnaireBuilderVersionResource::collection(
                $this->whenLoaded('versions'),
            ),
        ];
    }
}
