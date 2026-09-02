<?php

namespace App\Http\Requests;

class StoreTeacherRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'unique:teachers,user_id', 'exists:users,id'],
            'organization_unit_id' => ['required', 'integer', 'exists:organization_units,id'],
            'name' => ['required', 'string', 'max:255'],
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
