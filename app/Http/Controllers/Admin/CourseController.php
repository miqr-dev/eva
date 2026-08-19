<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('courses.manage');

        $courses = Course::query()
            ->with(['organizationUnit', 'teachers'])
            ->latest()
            ->paginate(25);

        return CourseResource::collection($courses);
    }

    public function store(StoreCourseRequest $request): CourseResource
    {
        $validated = $request->validated();

        $course = DB::transaction(function () use ($validated): Course {
            $course = Course::query()->create(Arr::except($validated, 'teacher_ids'));
            $course->teachers()->sync($validated['teacher_ids'] ?? []);

            return $course;
        });

        return new CourseResource($course->load(['organizationUnit', 'teachers']));
    }

    public function show(Course $course): CourseResource
    {
        $this->authorizePermission('courses.manage');

        return new CourseResource(
            $course->load(['organizationUnit', 'teachers']),
        );
    }

    public function update(UpdateCourseRequest $request, Course $course): CourseResource
    {
        $validated = $request->validated();

        DB::transaction(function () use ($course, $validated): void {
            $course->update(Arr::except($validated, 'teacher_ids'));

            if (array_key_exists('teacher_ids', $validated)) {
                $course->teachers()->sync($validated['teacher_ids']);
            }
        });

        return new CourseResource($course->load(['organizationUnit', 'teachers']));
    }

    public function destroy(Course $course): Response
    {
        $this->authorizePermission('courses.manage');

        $course->delete();

        return response()->noContent();
    }
}
