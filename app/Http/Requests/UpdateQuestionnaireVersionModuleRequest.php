<?php

namespace App\Http\Requests;

use App\Enums\RepeatMode;
use Illuminate\Validation\Rule;

class UpdateQuestionnaireVersionModuleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'repeat_mode' => ['required', Rule::enum(RepeatMode::class)],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
