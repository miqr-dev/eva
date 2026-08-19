<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use LogicException;

class UpdateUserRequest extends AdminRequest
{
    public function rules(): array
    {
        $user = $this->route('user');

        if (! $user instanceof User) {
            throw new LogicException('User route binding is missing.');
        }

        return [
            'organization_unit_id' => ['nullable', 'integer', 'exists:organization_units,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'password' => ['sometimes', 'nullable', 'string', Password::defaults()],
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
