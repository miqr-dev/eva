<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRoleRequest;
use App\Http\Requests\UpdateTeacherRoleRequest;
use App\Http\Resources\TeacherRoleResource;
use App\Models\TeacherRole;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class TeacherRoleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('courses.manage');

        $teacherRoles = TeacherRole::query()
            ->withCount('teachers')
            ->orderBy('name')
            ->paginate(25);

        return TeacherRoleResource::collection($teacherRoles);
    }

    public function store(StoreTeacherRoleRequest $request): TeacherRoleResource
    {
        $teacherRole = TeacherRole::query()->create($request->validated());

        return new TeacherRoleResource($teacherRole);
    }

    public function show(TeacherRole $teacherRole): TeacherRoleResource
    {
        $this->authorizePermission('courses.manage');

        return new TeacherRoleResource($teacherRole->loadCount('teachers'));
    }

    public function update(
        UpdateTeacherRoleRequest $request,
        TeacherRole $teacherRole,
    ): TeacherRoleResource {
        $teacherRole->update($request->validated());

        return new TeacherRoleResource($teacherRole);
    }

    public function destroy(TeacherRole $teacherRole): Response
    {
        $this->authorizePermission('courses.manage');

        $teacherRole->delete();

        return response()->noContent();
    }
}
