<?php

use App\Enums\ModuleTargetType;
use App\Enums\PublicationStatus;
use App\Enums\RepeatMode;
use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\Permission;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\QuestionnaireVersionModule;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function questionnaireBuilderUser(): User
{
    $user = User::factory()->create();
    $permission = Permission::query()->create([
        'name' => 'questionnaires.manage',
        'guard_name' => 'web',
    ]);
    $user->permissions()->attach($permission);

    return $user;
}

test('authorized users can open the questionnaire builder', function () {
    $user = questionnaireBuilderUser();
    $template = QuestionnaireTemplate::factory()->create();
    $version = QuestionnaireVersion::factory()->for($template)->create([
        'status' => PublicationStatus::Draft,
    ]);
    $module = Module::factory()->create(['name' => 'Organisation']);
    $moduleVersion = ModuleVersion::factory()
        ->for($module)
        ->create([
            'title' => 'Organisation',
            'status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);
    QuestionnaireVersionModule::query()->create([
        'questionnaire_version_id' => $version->id,
        'module_version_id' => $moduleVersion->id,
        'sort_order' => 0,
        'repeat_mode' => RepeatMode::Once,
    ]);

    $this->actingAs($user)
        ->get(route('admin.questionnaire-builder.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/QuestionnaireBuilder')
            ->has('templates', 1)
            ->where('templates.0.id', $template->id)
            ->where('templates.0.versions.0.id', $version->id)
            ->where('templates.0.versions.0.modules.0.module_version.module_name', 'Organisation')
            ->has('availableModuleVersions', 1)
            ->where('availableModuleVersions.0.id', $moduleVersion->id)
        );
});

test('draft questionnaire versions can be assembled and published', function () {
    $user = questionnaireBuilderUser();
    $template = QuestionnaireTemplate::factory()->create([
        'name' => 'Kursbewertung',
    ]);
    $organizationModule = Module::factory()->create(['name' => 'Organisation']);
    $teacherModule = Module::factory()->create(['name' => 'Dozent / Unterricht']);
    $organizationVersion = ModuleVersion::factory()
        ->for($organizationModule)
        ->create([
            'title' => 'Organisation',
            'status' => PublicationStatus::Published,
            'target_type' => ModuleTargetType::None,
            'published_at' => now(),
        ]);
    $teacherVersion = ModuleVersion::factory()
        ->for($teacherModule)
        ->create([
            'title' => 'Dozent / Unterricht',
            'status' => PublicationStatus::Published,
            'target_type' => ModuleTargetType::Teacher,
            'published_at' => now(),
        ]);

    $versionResponse = $this->actingAs($user)->postJson(
        route('admin.api.questionnaire-versions.store'),
        [
            'questionnaire_template_id' => $template->id,
            'title' => 'Kursbewertung',
            'description' => 'Standardfragebogen für Kurse.',
            'default_language' => 'de',
            'min_answers_to_show_results' => 5,
        ],
    );

    $versionResponse
        ->assertCreated()
        ->assertJsonPath('data.version_number', 1)
        ->assertJsonPath('data.status', 'draft');

    $questionnaireVersion = QuestionnaireVersion::query()->firstOrFail();

    $this->actingAs($user)->postJson(
        route('admin.api.questionnaire-version-modules.store'),
        [
            'questionnaire_version_id' => $questionnaireVersion->id,
            'module_version_id' => $organizationVersion->id,
            'repeat_mode' => RepeatMode::Once->value,
        ],
    )->assertCreated()
        ->assertJsonPath('data.sort_order', 0)
        ->assertJsonPath('data.repeat_mode', 'once');

    $teacherLinkResponse = $this->actingAs($user)->postJson(
        route('admin.api.questionnaire-version-modules.store'),
        [
            'questionnaire_version_id' => $questionnaireVersion->id,
            'module_version_id' => $teacherVersion->id,
            'repeat_mode' => RepeatMode::PerTarget->value,
        ],
    );
    $teacherLinkResponse
        ->assertCreated()
        ->assertJsonPath('data.sort_order', 1)
        ->assertJsonPath('data.module_version.target_type', 'teacher');

    $teacherLink = QuestionnaireVersionModule::query()
        ->where('module_version_id', $teacherVersion->id)
        ->firstOrFail();

    $this->actingAs($user)->putJson(
        route('admin.api.questionnaire-version-modules.update', $teacherLink),
        [
            'repeat_mode' => RepeatMode::PerTarget->value,
            'sort_order' => 0,
        ],
    )->assertSuccessful()
        ->assertJsonPath('data.sort_order', 0);

    expect(
        QuestionnaireVersionModule::query()
            ->orderBy('sort_order')
            ->pluck('module_version_id')
            ->all(),
    )->toBe([$teacherVersion->id, $organizationVersion->id]);

    $this->actingAs($user)
        ->patchJson(route('admin.api.questionnaire-versions.publish', $questionnaireVersion))
        ->assertSuccessful()
        ->assertJsonPath('data.status', 'published');

    expect($questionnaireVersion->fresh()->status)->toBe(PublicationStatus::Published);
});

test('published questionnaire versions cannot be changed', function () {
    $user = questionnaireBuilderUser();
    $version = QuestionnaireVersion::factory()->create([
        'status' => PublicationStatus::Published,
        'published_at' => now(),
    ]);
    $moduleVersion = ModuleVersion::factory()->create([
        'status' => PublicationStatus::Published,
        'published_at' => now(),
    ]);

    $this->actingAs($user)->putJson(
        route('admin.api.questionnaire-versions.update', $version),
        [
            'title' => 'Neue Version',
            'description' => null,
            'default_language' => 'de',
            'min_answers_to_show_results' => 5,
        ],
    )->assertUnprocessable()
        ->assertJsonValidationErrors('questionnaire_version_id');

    $this->actingAs($user)->postJson(
        route('admin.api.questionnaire-version-modules.store'),
        [
            'questionnaire_version_id' => $version->id,
            'module_version_id' => $moduleVersion->id,
            'repeat_mode' => RepeatMode::Once->value,
        ],
    )->assertUnprocessable()
        ->assertJsonValidationErrors('questionnaire_version_id');
});
