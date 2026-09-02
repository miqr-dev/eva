<?php

namespace App\Http\Requests;

use App\Enums\ModuleTargetType;
use Illuminate\Validation\Rule;

class StoreModuleVersionRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'module_id' => ['required', 'integer', 'exists:modules,id'],
            'source_version_id' => [
                'nullable',
                'integer',
                Rule::exists('module_versions', 'id')->where(
                    'module_id',
                    $this->integer('module_id'),
                ),
            ],
            'target_type' => ['nullable', Rule::enum(ModuleTargetType::class)],
            'target_role_id' => ['nullable', 'integer', 'exists:teacher_roles,id'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
