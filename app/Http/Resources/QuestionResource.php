<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Question
 */
class QuestionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module_section_id' => $this->module_section_id,
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'scale_min' => $this->scale_min,
            'scale_max' => $this->scale_max,
            'scale_min_label' => $this->scale_min_label,
            'scale_max_label' => $this->scale_max_label,
            'scale_labels' => $this->scale_labels,
            'is_required' => $this->is_required,
            'sort_order' => $this->sort_order,
            'options' => QuestionOptionResource::collection(
                $this->whenLoaded('options'),
            ),
        ];
    }
}
