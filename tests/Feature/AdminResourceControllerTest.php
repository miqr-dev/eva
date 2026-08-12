<?php

use App\Enums\PublicationStatus;
use App\Models\Course;
use App\Models\OrganizationUnit;
use App\Models\Permission;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\Teacher;
use App\Models\User;

function userWithPermission(string $permission): User
{
    $user = User::factory()->create();
    $permissionModel = Permission::query()->create([
        'name' => $permission,
        'guard_name' => 'web',
    ]);
    $user->permissions()->attach($permissionModel);

    return $user;
}

test('admin endpoints require authentication', function () {
    $this->getJson(route('admin.api.courses.index'))
        ->assertUnauthorized();
});

test('users without the required permission are forbidden', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('admin.api.courses.store'), [])
        ->assertForbidden();
});

test('authorized users can create a course and assign teachers', function () {
    $user = userWithPermission('courses.manage');
    $organizationUnit = OrganizationUnit::factory()->create();
    $teacher = Teacher::factory()->for($organizationUnit)->create();

    $response = $this->actingAs($user)->postJson(
        route('admin.api.courses.store'),
        [
            'organization_unit_id' => $organizationUnit->id,
            'name' => 'Software Engineering',
            'code' => 'SE-201',
            'starts_at' => now()->toDateTimeString(),
            'ends_at' => now()->addMonths(4)->toDateTimeString(),
            'is_active' => true,
            'teacher_ids' => [$teacher->id],
        ],
    );

    $response
        ->assertCreated()
        ->assertJsonPath('data.code', 'SE-201')
        ->assertJsonPath('data.teachers.0.id', $teacher->id);

    $course = Course::query()->where('code', 'SE-201')->firstOrFail();

    expect($course->teachers)->toHaveCount(1)
        ->and($course->teachers->first()->is($teacher))->toBeTrue();
});

test('course codes only need to be unique inside their organization unit', function () {
    $user = userWithPermission('courses.manage');
    $firstOrganization = OrganizationUnit::factory()->create();
    $secondOrganization = OrganizationUnit::factory()->create();
    Course::factory()->for($firstOrganization)->create(['code' => 'SHARED-1']);

    $this->actingAs($user)->postJson(
        route('admin.api.courses.store'),
        [
            'organization_unit_id' => $secondOrganization->id,
            'name' => 'Shared Code Course',
            'code' => 'SHARED-1',
        ],
    )->assertCreated();
});

test('campaigns require a published questionnaire version', function () {
    $user = userWithPermission('campaigns.manage');
    $organizationUnit = OrganizationUnit::factory()->create();
    $template = QuestionnaireTemplate::factory()->create(['created_by_id' => $user->id]);
    $draftVersion = QuestionnaireVersion::factory()
        ->for($template)
        ->create([
            'created_by_id' => $user->id,
            'status' => PublicationStatus::Draft,
        ]);

    $payload = [
        'organization_unit_id' => $organizationUnit->id,
        'questionnaire_version_id' => $draftVersion->id,
        'title' => 'Draft Questionnaire Campaign',
    ];

    $this->actingAs($user)
        ->postJson(route('admin.api.evaluation-campaigns.store'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('questionnaire_version_id');

    $draftVersion->update([
        'status' => PublicationStatus::Published,
        'published_at' => now(),
    ]);

    $this->actingAs($user)
        ->postJson(route('admin.api.evaluation-campaigns.store'), $payload)
        ->assertCreated()
        ->assertJsonPath('data.title', 'Draft Questionnaire Campaign');
});
