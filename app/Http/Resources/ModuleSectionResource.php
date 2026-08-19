<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ModuleSection
 */
class ModuleSectionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module_version_id' => $this->module_version_id,
            'title' => $this->title,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'questions' => QuestionResource::collection(
                $this->whenLoaded('questions'),
            ),
        ];
    }
}
