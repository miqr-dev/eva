<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationUnitRequest;
use App\Http\Requests\UpdateOrganizationUnitRequest;
use App\Http\Resources\OrganizationUnitResource;
use App\Models\OrganizationUnit;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class OrganizationUnitController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('organization_units.manage');

        $organizationUnits = OrganizationUnit::query()
            ->with('parent')
            ->withCount(['children', 'users', 'courses'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(25);

        return OrganizationUnitResource::collection($organizationUnits);
    }

    public function store(StoreOrganizationUnitRequest $request): OrganizationUnitResource
    {
        $organizationUnit = OrganizationUnit::query()->create($request->validated());

        return new OrganizationUnitResource($organizationUnit->load('parent'));
    }

    public function show(OrganizationUnit $organizationUnit): OrganizationUnitResource
    {
        $this->authorizePermission('organization_units.manage');

        return new OrganizationUnitResource(
            $organizationUnit->load(['parent', 'children']),
        );
    }

    public function update(
        UpdateOrganizationUnitRequest $request,
        OrganizationUnit $organizationUnit,
    ): OrganizationUnitResource {
        $organizationUnit->update($request->validated());

        return new OrganizationUnitResource($organizationUnit->load('parent'));
    }

    public function destroy(OrganizationUnit $organizationUnit): Response
    {
        $this->authorizePermission('organization_units.manage');

        $organizationUnit->delete();

        return response()->noContent();
    }
}
