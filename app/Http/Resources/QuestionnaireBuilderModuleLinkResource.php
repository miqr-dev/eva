<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\QuestionnaireVersionModule
 */
class QuestionnaireBuilderModuleLinkResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'questionnaire_version_id' => $this->questionnaire_version_id,
            'module_version_id' => $this->module_version_id,
            'sort_order' => $this->sort_order,
            'repeat_mode' => $this->repeat_mode,
            'module_version' => new AvailableModuleVersionResource(
                $this->whenLoaded('moduleVersion'),
            ),
        ];
    }
}
