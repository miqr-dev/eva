<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use LogicException;

class UpdateTeacherRequest extends AdminRequest
{
    public function rules(): array
    {
        $teacher = $this->route('teacher');

        if (! $teacher instanceof Teacher) {
            throw new LogicException('Teacher route binding is missing.');
        }

        return [
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                Rule::unique('teachers', 'user_id')->ignore($teacher),
            ],
            'organization_unit_id' => ['sometimes', 'required', 'integer', 'exists:organization_units,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'teacher_role_id' => ['nullable', 'integer', 'exists:teacher_roles,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'is_remote' => ['sometimes', 'boolean'],
            'course_assignments' => ['sometimes', 'array'],
            'course_assignments.*.course_id' => ['required', 'integer', 'exists:courses,id'],
            'course_assignments.*.subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->ensureAssignmentsAreDistinct($validator, 'course_assignments', 'course_id');
        });
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
