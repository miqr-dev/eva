<?php

namespace App\Http\Controllers\Evaluation;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitEvaluationResponseRequest;
use App\Models\Tan;
use App\Services\QuestionnaireRenderService;
use App\Services\ResponseSubmissionService;
use App\Services\TanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FormController extends Controller
{
    public function show(
        Request $request,
        string $session,
        QuestionnaireRenderService $questionnaireRenderService,
        TanService $tanService,
    ): Response|RedirectResponse {
        $tan = $this->sessionTan($request, $session);

        if (! $tan instanceof Tan) {
            return $this->invalidSessionRedirect($request);
        }

        $tanService->ensureCanBeUsed($tan);

        return Inertia::render('evaluation/Form', [
            'session' => $session,
            'form' => $questionnaireRenderService->render(
                $tan->evaluationCampaign,
            ),
        ]);
    }

    public function store(
        SubmitEvaluationResponseRequest $request,
        string $session,
        ResponseSubmissionService $responseSubmissionService,
    ): RedirectResponse {
        $tan = $this->sessionTan($request, $session);

        if (! $tan instanceof Tan) {
            return $this->invalidSessionRedirect($request);
        }

        $responseSubmissionService->submit(
            $tan,
            $request->array('answers'),
            $request->string('language', 'de')->toString(),
            $request->userAgent(),
            $request->ip(),
        );

        $request->session()->forget([
            'evaluation.session',
            'evaluation.tan_id',
        ]);

        return to_route('evaluation.finished');
    }

    public function finished(): Response
    {
        return Inertia::render('evaluation/Finished');
    }

    private function sessionTan(Request $request, string $session): ?Tan
    {
        if ($request->session()->get('evaluation.session') !== $session) {
            return null;
        }

        $tanId = $request->session()->get('evaluation.tan_id');

        if (! is_numeric($tanId)) {
            return null;
        }

        return Tan::query()
            ->with('evaluationCampaign')
            ->find((int) $tanId);
    }

    private function invalidSessionRedirect(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'evaluation.session',
            'evaluation.tan_id',
        ]);

        return to_route('evaluation.tan.create')
            ->withErrors([
                'tan_code' => 'Bitte geben Sie Ihre TAN erneut ein.',
            ]);
    }
}
