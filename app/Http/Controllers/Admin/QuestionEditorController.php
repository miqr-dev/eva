<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionEditorModuleResource;
use App\Http\Resources\TeacherRoleResource;
use App\Models\Module;
use App\Models\TeacherRole;
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

        $teacherRoles = TeacherRole::query()->orderBy('name')->get();
        $teacherRoleData = TeacherRoleResource::collection($teacherRoles)
            ->response($request)
            ->getData(true);

        return Inertia::render('admin/QuestionEditor', [
            'modules' => $responseData['data'] ?? [],
            'teacherRoles' => $teacherRoleData['data'] ?? [],
        ]);
    }
}
