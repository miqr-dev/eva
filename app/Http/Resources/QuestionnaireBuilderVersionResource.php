<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\QuestionnaireVersion
 */
class QuestionnaireBuilderVersionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'questionnaire_template_id' => $this->questionnaire_template_id,
            'version_number' => $this->version_number,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'default_language' => $this->default_language,
            'min_answers_to_show_results' => $this->min_answers_to_show_results,
            'published_at' => $this->published_at,
            'modules' => QuestionnaireBuilderModuleLinkResource::collection(
                $this->whenLoaded('moduleLinks'),
            ),
        ];
    }
}
