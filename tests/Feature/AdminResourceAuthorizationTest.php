<?php

use App\Models\BenchmarkGroup;
use App\Models\Course;
use App\Models\EmailTemplate;
use App\Models\EvaluationCampaign;
use App\Models\Module;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use App\Models\OrganizationUnit;
use App\Models\Permission;
use App\Models\Question;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\QuestionnaireVersionModule;
use App\Models\ReportTemplate;
use App\Models\Teacher;
use App\Models\User;

function userWithAdminPermission(string $permission): User
{
    $user = User::factory()->create();
    $permissionModel = Permission::query()->create([
        'name' => $permission,
        'guard_name' => 'web',
    ]);
    $user->permissions()->attach($permissionModel);

    return $user;
}

dataset('admin resources', [
    'organization units' => ['admin.api.organization-units', 'organization_units.manage', fn () => OrganizationUnit::factory()->create()],
    'users' => ['admin.api.users', 'users.manage', fn () => User::factory()->create()],
    'courses' => ['admin.api.courses', 'courses.manage', fn () => Course::factory()->create()],
    'teachers' => ['admin.api.teachers', 'courses.manage', fn () => Teacher::factory()->create()],
    'questionnaire templates' => ['admin.api.questionnaire-templates', 'questionnaires.manage', fn () => QuestionnaireTemplate::factory()->create()],
    'modules' => ['admin.api.modules', 'questionnaires.manage', fn () => Module::factory()->create()],
    'evaluation campaigns' => ['admin.api.evaluation-campaigns', 'campaigns.manage', fn () => EvaluationCampaign::factory()->create()],
    'report templates' => ['admin.api.report-templates', 'reports.manage', fn () => ReportTemplate::factory()->create()],
    'benchmark groups' => ['admin.api.benchmark-groups', 'benchmarks.manage', fn () => BenchmarkGroup::factory()->create()],
    'email templates' => ['admin.api.email-templates', 'emails.manage', fn () => EmailTemplate::factory()->create()],
]);

test('users without the required permission cannot list, view, or delete a resource', function (
    string $routeBase,
    string $permission,
    Closure $makeRecord,
) {
    $user = User::factory()->create();
    $record = $makeRecord();

    $this->actingAs($user)
        ->getJson(route("{$routeBase}.index"))
        ->assertForbidden();

    $this->actingAs($user)
        ->getJson(route("{$routeBase}.show", $record))
        ->assertForbidden();

    $this->actingAs($user)
        ->deleteJson(route("{$routeBase}.destroy", $record))
        ->assertForbidden();

    expect($record->fresh())->not->toBeNull();
})->with('admin resources');

test('users with the required permission can list, view, and delete a resource', function (
    string $routeBase,
    string $permission,
    Closure $makeRecord,
) {
    $user = userWithAdminPermission($permission);
    $record = $makeRecord();

    $this->actingAs($user)
        ->getJson(route("{$routeBase}.index"))
        ->assertOk();

    $this->actingAs($user)
        ->getJson(route("{$routeBase}.show", $record))
        ->assertOk();

    $this->actingAs($user)
        ->deleteJson(route("{$routeBase}.destroy", $record))
        ->assertNoContent();
})->with('admin resources');

test('deleting a module section requires the questionnaires permission', function () {
    $moduleVersion = ModuleVersion::factory()->create();
    $section = ModuleSection::factory()->for($moduleVersion)->create();

    $unauthorizedUser = User::factory()->create();
    $this->actingAs($unauthorizedUser)
        ->deleteJson(route('admin.api.module-sections.destroy', $section))
        ->assertForbidden();

    $authorizedUser = userWithAdminPermission('questionnaires.manage');
    $this->actingAs($authorizedUser)
        ->deleteJson(route('admin.api.module-sections.destroy', $section))
        ->assertNoContent();
});

test('deleting a question requires the questionnaires permission', function () {
    $section = ModuleSection::factory()->create();
    $question = Question::factory()->for($section, 'moduleSection')->create();

    $unauthorizedUser = User::factory()->create();
    $this->actingAs($unauthorizedUser)
        ->deleteJson(route('admin.api.questions.destroy', $question))
        ->assertForbidden();

    $authorizedUser = userWithAdminPermission('questionnaires.manage');
    $this->actingAs($authorizedUser)
        ->deleteJson(route('admin.api.questions.destroy', $question))
        ->assertNoContent();
});

test('unlinking a module from a questionnaire version requires the questionnaires permission', function () {
    $questionnaireVersion = QuestionnaireVersion::factory()->create();
    $moduleVersion = ModuleVersion::factory()->create();
    $link = QuestionnaireVersionModule::query()->create([
        'questionnaire_version_id' => $questionnaireVersion->id,
        'module_version_id' => $moduleVersion->id,
        'sort_order' => 0,
        'repeat_mode' => 'once',
    ]);

    $unauthorizedUser = User::factory()->create();
    $this->actingAs($unauthorizedUser)
        ->deleteJson(route('admin.api.questionnaire-version-modules.destroy', $link))
        ->assertForbidden();

    $authorizedUser = userWithAdminPermission('questionnaires.manage');
    $this->actingAs($authorizedUser)
        ->deleteJson(route('admin.api.questionnaire-version-modules.destroy', $link))
        ->assertNoContent();
});
