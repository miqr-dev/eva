<?php

namespace App\Http\Controllers\Evaluation;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateTanRequest;
use App\Services\TanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TanController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('evaluation/EnterTan');
    }

    public function store(
        ValidateTanRequest $request,
        TanService $tanService,
    ): RedirectResponse {
        $tan = $tanService->validateForStart(
            $request->string('tan_code')->toString(),
        );
        $sessionToken = Str::random(40);

        $request->session()->put([
            'evaluation.session' => $sessionToken,
            'evaluation.tan_id' => $tan->id,
        ]);

        return to_route('evaluation.form.show', [
            'session' => $sessionToken,
        ]);
    }
}
