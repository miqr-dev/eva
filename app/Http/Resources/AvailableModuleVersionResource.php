<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ModuleVersion
 */
class AvailableModuleVersionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module_id' => $this->module_id,
            'module_name' => $this->whenLoaded(
                'module',
                fn (): ?string => $this->module?->name,
            ),
            'version_number' => $this->version_number,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'target_type' => $this->target_type,
            'target_role_id' => $this->target_role_id,
            'target_role' => new TeacherRoleResource(
                $this->whenLoaded('targetRole'),
            ),
            'default_language' => $this->default_language,
        ];
    }
}
