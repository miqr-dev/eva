<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBenchmarkGroupRequest;
use App\Http\Requests\UpdateBenchmarkGroupRequest;
use App\Http\Resources\BenchmarkGroupResource;
use App\Models\BenchmarkGroup;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class BenchmarkGroupController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('benchmarks.manage');

        $benchmarkGroups = BenchmarkGroup::query()
            ->with('organizationUnit')
            ->withCount('snapshots')
            ->latest()
            ->paginate(25);

        return BenchmarkGroupResource::collection($benchmarkGroups);
    }

    public function store(StoreBenchmarkGroupRequest $request): BenchmarkGroupResource
    {
        $benchmarkGroup = BenchmarkGroup::query()->create($request->validated());

        return new BenchmarkGroupResource($benchmarkGroup->load('organizationUnit'));
    }

    public function show(BenchmarkGroup $benchmarkGroup): BenchmarkGroupResource
    {
        $this->authorizePermission('benchmarks.manage');

        return new BenchmarkGroupResource(
            $benchmarkGroup->load('organizationUnit'),
        );
    }

    public function update(
        UpdateBenchmarkGroupRequest $request,
        BenchmarkGroup $benchmarkGroup,
    ): BenchmarkGroupResource {
        $benchmarkGroup->update($request->validated());

        return new BenchmarkGroupResource($benchmarkGroup->load('organizationUnit'));
    }

    public function destroy(BenchmarkGroup $benchmarkGroup): Response
    {
        $this->authorizePermission('benchmarks.manage');

        $benchmarkGroup->delete();

        return response()->noContent();
    }
}
