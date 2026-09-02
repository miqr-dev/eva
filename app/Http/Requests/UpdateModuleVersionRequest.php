<?php

namespace App\Http\Requests;

use App\Enums\ModuleTargetType;
use Illuminate\Validation\Rule;

class UpdateModuleVersionRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'target_type' => ['sometimes', 'required', Rule::enum(ModuleTargetType::class)],
            'target_role_id' => ['nullable', 'integer', 'exists:teacher_roles,id'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
