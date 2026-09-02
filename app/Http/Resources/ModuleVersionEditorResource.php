<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ModuleVersion
 */
class ModuleVersionEditorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module_id' => $this->module_id,
            'version_number' => $this->version_number,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'default_language' => $this->default_language,
            'target_type' => $this->target_type,
            'target_role_id' => $this->target_role_id,
            'target_role' => new TeacherRoleResource(
                $this->whenLoaded('targetRole'),
            ),
            'published_at' => $this->published_at,
            'sections' => ModuleSectionResource::collection(
                $this->whenLoaded('sections'),
            ),
        ];
    }
}
