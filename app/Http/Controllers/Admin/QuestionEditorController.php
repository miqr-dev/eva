<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionEditorModuleResource;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuestionEditorController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        abort_unless(
            $user instanceof User && $user->hasPermission('questionnaires.manage'),
            403,
        );

        $modules = Module::query()
            ->with('versions.sections.questions.options')
            ->orderBy('name')
            ->get();
        $responseData = QuestionEditorModuleResource::collection($modules)
            ->response($request)
            ->getData(true);

        return Inertia::render('admin/QuestionEditor', [
            'modules' => $responseData['data'] ?? [],
        ]);
    }
}
