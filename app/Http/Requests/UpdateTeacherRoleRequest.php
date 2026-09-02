<?php

namespace App\Http\Requests;

use App\Models\TeacherRole;
use Illuminate\Validation\Rule;
use LogicException;

class UpdateTeacherRoleRequest extends AdminRequest
{
    public function rules(): array
    {
        $teacherRole = $this->route('teacher_role');

        if (! $teacherRole instanceof TeacherRole) {
            throw new LogicException('TeacherRole route binding is missing.');
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teacher_roles', 'name')->ignore($teacherRole),
            ],
        ];
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
