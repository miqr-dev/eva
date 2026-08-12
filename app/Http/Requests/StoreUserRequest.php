<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'organization_unit_id' => ['nullable', 'integer', 'exists:organization_units,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
            'is_active' => ['sometimes', 'boolean'],
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer', 'distinct', 'exists:roles,id'],
            'permission_ids' => ['sometimes', 'array'],
            'permission_ids.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ];
    }

    protected function permission(): string
    {
        return 'users.manage';
    }
}
