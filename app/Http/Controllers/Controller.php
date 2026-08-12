<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function authorizePermission(string $permission): void
    {
        abort_unless(
            request()->user()?->hasPermission($permission) ?? false,
            403,
        );
    }
}
