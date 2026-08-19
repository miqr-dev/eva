<?php

namespace App\Http\Requests;

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
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
