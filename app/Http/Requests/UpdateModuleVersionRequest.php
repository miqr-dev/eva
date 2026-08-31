<?php

namespace App\Http\Requests;

use App\Enums\ModuleTargetType;
use Illuminate\Validation\Rule;

class UpdateModuleVersionRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'target_type' => ['required', Rule::enum(ModuleTargetType::class)],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
