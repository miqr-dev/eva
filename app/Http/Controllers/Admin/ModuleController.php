<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ModuleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('questionnaires.manage');

        $modules = Module::query()
            ->with('creator')
            ->withCount('versions')
            ->latest()
            ->paginate(25);

        return ModuleResource::collection($modules);
    }

    public function store(StoreModuleRequest $request): ModuleResource
    {
        $module = Module::query()->create([
            ...$request->validated(),
            'created_by_id' => $request->user()->id,
        ]);

        return new ModuleResource($module->load('creator'));
    }

    public function show(Module $module): ModuleResource
    {
        $this->authorizePermission('questionnaires.manage');

        return new ModuleResource($module->load(['creator', 'versions']));
    }

    public function update(UpdateModuleRequest $request, Module $module): ModuleResource
    {
        $module->update($request->validated());

        return new ModuleResource($module->load('creator'));
    }

    public function destroy(Module $module): Response
    {
        $this->authorizePermission('questionnaires.manage');

        $module->delete();

        return response()->noContent();
    }
}
