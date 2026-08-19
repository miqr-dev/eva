<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('users.manage');

        $users = User::query()
            ->with(['organizationUnit', 'roles'])
            ->latest()
            ->paginate(25);

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated): User {
            $user = User::query()->create(Arr::except(
                $validated,
                ['role_ids', 'permission_ids'],
            ));
            $user->roles()->sync($validated['role_ids'] ?? []);
            $user->permissions()->sync($validated['permission_ids'] ?? []);

            return $user;
        });

        return new UserResource($user->load(['organizationUnit', 'roles', 'permissions']));
    }

    public function show(User $user): UserResource
    {
        $this->authorizePermission('users.manage');

        return new UserResource(
            $user->load(['organizationUnit', 'teacher', 'roles', 'permissions']),
        );
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated): void {
            $attributes = Arr::except($validated, ['role_ids', 'permission_ids']);

            if (($attributes['password'] ?? null) === null) {
                unset($attributes['password']);
            }

            $user->update($attributes);

            if (array_key_exists('role_ids', $validated)) {
                $user->roles()->sync($validated['role_ids']);
            }

            if (array_key_exists('permission_ids', $validated)) {
                $user->permissions()->sync($validated['permission_ids']);
            }
        });

        return new UserResource($user->load(['organizationUnit', 'roles', 'permissions']));
    }

    public function destroy(User $user): Response
    {
        $this->authorizePermission('users.manage');

        $user->delete();

        return response()->noContent();
    }
}
