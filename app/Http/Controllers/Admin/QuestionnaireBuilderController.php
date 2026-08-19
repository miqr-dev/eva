<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableModuleVersionResource;
use App\Http\Resources\QuestionnaireBuilderTemplateResource;
use App\Models\ModuleVersion;
use App\Models\QuestionnaireTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuestionnaireBuilderController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        abort_unless(
            $user instanceof User && $user->hasPermission('questionnaires.manage'),
            403,
        );

        $templates = QuestionnaireTemplate::query()
            ->with('versions.moduleLinks.moduleVersion.module')
            ->orderBy('name')
            ->get();
        $availableModuleVersions = ModuleVersion::query()
            ->where('status', 'published')
            ->with('module')
            ->orderBy('title')
            ->get();

        $templateData = QuestionnaireBuilderTemplateResource::collection(
            $templates,
        )->response($request)->getData(true);
        $moduleVersionData = AvailableModuleVersionResource::collection(
            $availableModuleVersions,
        )->response($request)->getData(true);

        return Inertia::render('admin/QuestionnaireBuilder', [
            'templates' => $templateData['data'] ?? [],
            'availableModuleVersions' => $moduleVersionData['data'] ?? [],
        ]);
    }
}
