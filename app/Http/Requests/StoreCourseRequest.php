<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
            'teacher_assignments' => ['sometimes', 'array'],
            'teacher_assignments.*.teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'teacher_assignments.*.subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->ensureAssignmentsAreDistinct($validator, 'teacher_assignments', 'teacher_id');
        });
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
