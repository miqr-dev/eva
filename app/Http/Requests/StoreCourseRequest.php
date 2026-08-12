<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreCourseRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'organization_unit_id' => ['required', 'integer', 'exists:organization_units,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses')->where(
                    fn ($query) => $query->where(
                        'organization_unit_id',
                        $this->integer('organization_unit_id'),
                    ),
                ),
            ],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
            'teacher_ids' => ['sometimes', 'array'],
            'teacher_ids.*' => ['integer', 'distinct', 'exists:teachers,id'],
        ];
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
