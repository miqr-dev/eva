<?php

namespace App\Http\Requests;

use App\Enums\RepeatMode;
use Illuminate\Validation\Rule;

class StoreQuestionnaireVersionModuleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'questionnaire_version_id' => ['required', 'integer', 'exists:questionnaire_versions,id'],
            'module_version_id' => ['required', 'integer', 'exists:module_versions,id'],
            'repeat_mode' => ['required', Rule::enum(RepeatMode::class)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
