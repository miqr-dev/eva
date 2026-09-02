<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('courses.manage');

        $teachers = Teacher::query()
            ->with(['organizationUnit', 'teacherRole', 'user', 'courses'])
            ->latest()
            ->paginate(25);

        return TeacherResource::collection($teachers);
    }

    public function store(StoreTeacherRequest $request): TeacherResource
    {
        $validated = $request->validated();

        $teacher = DB::transaction(function () use ($validated): Teacher {
            $teacher = Teacher::query()->create(Arr::except($validated, 'course_ids'));
            $teacher->courses()->sync($validated['course_ids'] ?? []);

            return $teacher;
        });

        return new TeacherResource($teacher->load(['organizationUnit', 'teacherRole', 'user', 'courses']));
    }

    public function show(Teacher $teacher): TeacherResource
    {
        $this->authorizePermission('courses.manage');

        return new TeacherResource(
            $teacher->load(['organizationUnit', 'teacherRole', 'user', 'courses']),
        );
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): TeacherResource
    {
        $validated = $request->validated();

        DB::transaction(function () use ($teacher, $validated): void {
            $teacher->update(Arr::except($validated, 'course_ids'));

            if (array_key_exists('course_ids', $validated)) {
                $teacher->courses()->sync($validated['course_ids']);
            }
        });

        return new TeacherResource($teacher->load(['organizationUnit', 'teacherRole', 'user', 'courses']));
    }

    public function destroy(Teacher $teacher): Response
    {
        $this->authorizePermission('courses.manage');

        $teacher->delete();

        return response()->noContent();
    }
}
