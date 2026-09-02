<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationCampaign;
use App\Models\User;
use App\Services\QuestionnaireRenderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EvaluationCampaignPreviewController extends Controller
{
    public function show(
        Request $request,
        EvaluationCampaign $evaluationCampaign,
        QuestionnaireRenderService $questionnaireRenderService,
    ): Response {
        $user = $request->user();

        abort_unless(
            $user instanceof User && $user->hasPermission('campaigns.manage'),
            403,
        );

        return Inertia::render('evaluation/Form', [
            'session' => null,
            'preview' => true,
            'form' => $questionnaireRenderService->render($evaluationCampaign),
        ]);
    }
}
