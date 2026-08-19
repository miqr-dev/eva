<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportTemplateRequest;
use App\Http\Requests\UpdateReportTemplateRequest;
use App\Http\Resources\ReportTemplateResource;
use App\Models\ReportTemplate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ReportTemplateController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('reports.manage');

        $reportTemplates = ReportTemplate::query()
            ->with('creator')
            ->withCount('sections')
            ->latest()
            ->paginate(25);

        return ReportTemplateResource::collection($reportTemplates);
    }

    public function store(StoreReportTemplateRequest $request): ReportTemplateResource
    {
        $reportTemplate = ReportTemplate::query()->create([
            ...$request->validated(),
            'created_by_id' => $request->user()->id,
        ]);

        return new ReportTemplateResource($reportTemplate->load('creator'));
    }

    public function show(ReportTemplate $reportTemplate): ReportTemplateResource
    {
        $this->authorizePermission('reports.manage');

        return new ReportTemplateResource(
            $reportTemplate->load(['creator', 'sections']),
        );
    }

    public function update(
        UpdateReportTemplateRequest $request,
        ReportTemplate $reportTemplate,
    ): ReportTemplateResource {
        $reportTemplate->update($request->validated());

        return new ReportTemplateResource($reportTemplate->load('creator'));
    }

    public function destroy(ReportTemplate $reportTemplate): Response
    {
        $this->authorizePermission('reports.manage');

        $reportTemplate->delete();

        return response()->noContent();
    }
}
