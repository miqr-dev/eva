<?php

namespace App\Http\Requests;

use App\Enums\OrganizationUnitType;
use App\Models\OrganizationUnit;
use LogicException;
use Illuminate\Validation\Rule;

class UpdateOrganizationUnitRequest extends AdminRequest
{
    public function rules(): array
    {
        $organizationUnit = $this->route('organization_unit');

        if (! $organizationUnit instanceof OrganizationUnit) {
            throw new LogicException('Organization unit route binding is missing.');
        }

        return [
            'parent_id' => [
                'nullable',
                'integer',
                'exists:organization_units,id',
                Rule::notIn([$organizationUnit->id]),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', Rule::enum(OrganizationUnitType::class)],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'organization_units.manage';
    }
}
