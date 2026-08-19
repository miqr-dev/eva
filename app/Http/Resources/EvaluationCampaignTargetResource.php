<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\EvaluationCampaignTarget
 */
class EvaluationCampaignTargetResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'target_type' => $this->target_type,
            'target_id' => $this->target_id,
            'label' => $this->label,
            'sort_order' => $this->sort_order,
        ];
    }
}
