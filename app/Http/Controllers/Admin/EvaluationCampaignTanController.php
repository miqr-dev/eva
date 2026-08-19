<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateTansRequest;
use App\Http\Resources\EvaluationCampaignResource;
use App\Models\EvaluationCampaign;
use App\Models\User;
use App\Services\TanGenerationService;
use DateTimeInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class EvaluationCampaignTanController extends Controller
{
    public function show(Request $request, EvaluationCampaign $evaluationCampaign): Response
    {
        $user = $request->user();

        abort_unless(
            $user instanceof User && $user->hasPermission('campaigns.manage'),
            403,
        );

        $this->loadCampaignForTanPage($evaluationCampaign);

        return Inertia::render('admin/EvaluationTans', [
            'campaign' => $this->campaignData($request, $evaluationCampaign),
            'tanStats' => $this->tanStatistics($evaluationCampaign),
            'evaluationUrl' => route('evaluation.tan.create'),
        ]);
    }

    public function store(
        GenerateTansRequest $request,
        EvaluationCampaign $evaluationCampaign,
        TanGenerationService $tanGenerationService,
    ): JsonResponse {
        $validated = $request->validated();
        $codes = $tanGenerationService->generate(
            $evaluationCampaign,
            (int) $validated['amount'],
        );

        $this->loadCampaignForTanPage($evaluationCampaign->refresh());

        return response()->json([
            'message' => 'Die TANs wurden erzeugt. Sie werden nur jetzt im Klartext angezeigt.',
            'tans' => $codes,
            'stats' => $this->tanStatistics($evaluationCampaign),
            'evaluation_url' => route('evaluation.tan.create'),
            'expires_at' => $this->formatDateTime($evaluationCampaign->ends_at),
        ], HttpResponse::HTTP_CREATED);
    }

    private function loadCampaignForTanPage(EvaluationCampaign $evaluationCampaign): void
    {
        $evaluationCampaign
            ->load(['organizationUnit', 'course', 'questionnaireVersion'])
            ->loadCount(['targets', 'responses', 'tans']);
    }

    /**
     * @return array<string, mixed>
     */
    private function campaignData(Request $request, EvaluationCampaign $evaluationCampaign): array
    {
        $responseData = (new EvaluationCampaignResource($evaluationCampaign))
            ->response($request)
            ->getData(true);
        $data = $responseData['data'] ?? [];

        return is_array($data) ? $data : [];
    }

    /**
     * @return array{total: int, unused: int, started: int, used: int, inactive: int}
     */
    private function tanStatistics(EvaluationCampaign $evaluationCampaign): array
    {
        return [
            'total' => $evaluationCampaign->tans()->count(),
            'unused' => $evaluationCampaign->tans()
                ->where('is_active', true)
                ->whereNull('used_at')
                ->count(),
            'started' => $evaluationCampaign->tans()
                ->whereNotNull('started_at')
                ->count(),
            'used' => $evaluationCampaign->tans()
                ->whereNotNull('used_at')
                ->count(),
            'inactive' => $evaluationCampaign->tans()
                ->where('is_active', false)
                ->count(),
        ];
    }

    private function formatDateTime(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        return is_string($value) && $value !== '' ? $value : null;
    }
}
