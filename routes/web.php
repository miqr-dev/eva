<?php

use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\BenchmarkGroupController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EvaluationCampaignController;
use App\Http\Controllers\Admin\EvaluationCampaignTanController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleSectionController;
use App\Http\Controllers\Admin\ModuleVersionController;
use App\Http\Controllers\Admin\OrganizationUnitController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuestionEditorController;
use App\Http\Controllers\Admin\QuestionnaireBuilderController;
use App\Http\Controllers\Admin\QuestionnaireTemplateController;
use App\Http\Controllers\Admin\QuestionnaireVersionController;
use App\Http\Controllers\Admin\QuestionnaireVersionModuleController;
use App\Http\Controllers\Admin\ReportTemplateController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Evaluation\FormController as EvaluationFormController;
use App\Http\Controllers\Evaluation\TanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route(
    auth()->check() ? 'dashboard' : 'login',
))->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('evaluation', [TanController::class, 'create'])
    ->name('evaluation.tan.create');
Route::post('evaluation/tan', [TanController::class, 'store'])
    ->name('evaluation.tan.store');
Route::get('evaluation/form/{session}', [EvaluationFormController::class, 'show'])
    ->name('evaluation.form.show');
Route::post('evaluation/submit/{session}', [EvaluationFormController::class, 'store'])
    ->name('evaluation.form.store');
Route::get('evaluation/finished', [EvaluationFormController::class, 'finished'])
    ->name('evaluation.finished');

Route::middleware('auth')->group(function (): void {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('verwaltung/fragen', QuestionEditorController::class)
        ->name('admin.questions.index');
    Route::get('verwaltung/fragebogen-builder', QuestionnaireBuilderController::class)
        ->name('admin.questionnaire-builder.index');
    Route::get(
        'verwaltung/evaluationen/{evaluation_campaign}/tans',
        [EvaluationCampaignTanController::class, 'show'],
    )->name('admin.evaluation-campaigns.tans.show');
    Route::get('verwaltung/{resource}', AdminPageController::class)
        ->whereIn('resource', [
            'organisationseinheiten',
            'benutzer',
            'kurse',
            'lehrende',
            'frageboegen',
            'module',
            'evaluationen',
            'berichtsvorlagen',
            'benchmarks',
            'email-vorlagen',
        ])
        ->name('admin.resources.index');
    Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('auth')
    ->prefix('admin/api')
    ->name('admin.api.')
    ->group(function (): void {
        Route::apiResources([
            'organization-units' => OrganizationUnitController::class,
            'users' => UserController::class,
            'courses' => CourseController::class,
            'teachers' => TeacherController::class,
            'questionnaire-templates' => QuestionnaireTemplateController::class,
            'modules' => ModuleController::class,
            'evaluation-campaigns' => EvaluationCampaignController::class,
            'report-templates' => ReportTemplateController::class,
            'benchmark-groups' => BenchmarkGroupController::class,
            'email-templates' => EmailTemplateController::class,
        ]);
        Route::apiResource('module-versions', ModuleVersionController::class)
            ->only('store');
        Route::patch(
            'module-versions/{module_version}/publish',
            [ModuleVersionController::class, 'publish'],
        )->name('module-versions.publish');
        Route::apiResource('questionnaire-versions', QuestionnaireVersionController::class)
            ->only(['store', 'update']);
        Route::patch(
            'questionnaire-versions/{questionnaire_version}/publish',
            [QuestionnaireVersionController::class, 'publish'],
        )->name('questionnaire-versions.publish');
        Route::apiResource(
            'questionnaire-version-modules',
            QuestionnaireVersionModuleController::class,
        )->only(['store', 'update', 'destroy']);
        Route::apiResource('module-sections', ModuleSectionController::class)
            ->only(['store', 'update', 'destroy']);
        Route::apiResource('questions', QuestionController::class)
            ->only(['store', 'update', 'destroy']);
        Route::post(
            'evaluation-campaigns/{evaluation_campaign}/tans',
            [EvaluationCampaignTanController::class, 'store'],
        )->name('evaluation-campaigns.tans.store');
    });
