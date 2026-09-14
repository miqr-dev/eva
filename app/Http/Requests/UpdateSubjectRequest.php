<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Validation\Rule;
use LogicException;

class UpdateSubjectRequest extends AdminRequest
{
    public function rules(): array
    {
        $subject = $this->route('subject');

        if (! $subject instanceof Subject) {
            throw new LogicException('Subject route binding is missing.');
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'name')->ignore($subject),
            ],
            'code' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
