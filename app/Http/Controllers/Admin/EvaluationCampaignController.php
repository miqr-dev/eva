<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationCampaignRequest;
use App\Http\Requests\UpdateEvaluationCampaignRequest;
use App\Http\Resources\EvaluationCampaignResource;
use App\Models\EvaluationCampaign;
use App\Services\EvaluationCampaignTargetSyncService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class EvaluationCampaignController extends Controller
{
    public function __construct(
        private readonly EvaluationCampaignTargetSyncService $targetSyncService,
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('campaigns.manage');

        $evaluationCampaigns = EvaluationCampaign::query()
            ->with(['organizationUnit', 'course', 'questionnaireVersion'])
            ->withCount(['targets', 'responses', 'tans'])
            ->latest()
            ->paginate(25);

        return EvaluationCampaignResource::collection($evaluationCampaigns);
    }

    public function store(
        StoreEvaluationCampaignRequest $request,
    ): EvaluationCampaignResource {
        $evaluationCampaign = EvaluationCampaign::query()->create([
            ...$request->validated(),
            'created_by_id' => $request->user()->id,
        ]);

        $this->targetSyncService->syncCourseTeachers($evaluationCampaign);

        return new EvaluationCampaignResource(
            $evaluationCampaign->load([
                'organizationUnit',
                'course',
                'questionnaireVersion',
            ])->loadCount('targets'),
        );
    }

    public function show(
        EvaluationCampaign $evaluationCampaign,
    ): EvaluationCampaignResource {
        $this->authorizePermission('campaigns.manage');

        return new EvaluationCampaignResource(
            $evaluationCampaign->load([
                'organizationUnit',
                'course',
                'questionnaireVersion',
                'creator',
                'targets.target',
            ]),
        );
    }

    public function update(
        UpdateEvaluationCampaignRequest $request,
        EvaluationCampaign $evaluationCampaign,
    ): EvaluationCampaignResource {
        $evaluationCampaign->update($request->validated());
        $this->targetSyncService->syncCourseTeachers($evaluationCampaign);

        return new EvaluationCampaignResource(
            $evaluationCampaign->load([
                'organizationUnit',
                'course',
                'questionnaireVersion',
            ])->loadCount('targets'),
        );
    }

    public function destroy(EvaluationCampaign $evaluationCampaign): Response
    {
        $this->authorizePermission('campaigns.manage');

        $evaluationCampaign->delete();

        return response()->noContent();
    }
}
