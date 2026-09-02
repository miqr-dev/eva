<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use Illuminate\Validation\Rule;
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
            'course_ids' => ['sometimes', 'array'],
            'course_ids.*' => ['integer', 'distinct', 'exists:courses,id'],
        ];
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
