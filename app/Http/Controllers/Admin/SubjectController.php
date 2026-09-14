<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('courses.manage');

        $subjects = Subject::query()
            ->orderBy('name')
            ->paginate(25);

        return SubjectResource::collection($subjects);
    }

    public function store(StoreSubjectRequest $request): SubjectResource
    {
        $subject = Subject::query()->create($request->validated());

        return new SubjectResource($subject);
    }

    public function show(Subject $subject): SubjectResource
    {
        $this->authorizePermission('courses.manage');

        return new SubjectResource($subject);
    }

    public function update(
        UpdateSubjectRequest $request,
        Subject $subject,
    ): SubjectResource {
        $subject->update($request->validated());

        return new SubjectResource($subject);
    }

    public function destroy(Subject $subject): Response
    {
        $this->authorizePermission('courses.manage');

        $subject->delete();

        return response()->noContent();
    }
}
