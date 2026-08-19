<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\BenchmarkGroup
 */
class BenchmarkGroupResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'scope_type' => $this->scope_type,
            'organization_unit_id' => $this->organization_unit_id,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'organization_unit' => new OrganizationUnitResource(
                $this->whenLoaded('organizationUnit'),
            ),
            'snapshots_count' => $this->whenCounted('snapshots'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
