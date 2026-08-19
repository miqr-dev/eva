<?php

namespace App\Http\Requests;

use App\Enums\OrganizationUnitType;
use Illuminate\Validation\Rule;

class StoreOrganizationUnitRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:organization_units,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(OrganizationUnitType::class)],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'organization_units.manage';
    }
}
