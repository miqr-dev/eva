<?php

namespace App\Http\Requests;

class StoreTeacherRoleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:teacher_roles,name'],
        ];
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
