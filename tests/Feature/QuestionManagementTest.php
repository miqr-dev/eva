<?php

use App\Enums\PublicationStatus;
use App\Enums\QuestionType;
use App\Models\Module;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use App\Models\Permission;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\TeacherRole;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function questionEditorUser(): User
{
    $user = User::factory()->create();
    $permission = Permission::query()->create([
        'name' => 'questionnaires.manage',
        'guard_name' => 'web',
    ]);
    $user->permissions()->attach($permission);

    return $user;
}

test('authorized users can open the question editor', function () {
    $user = questionEditorUser();
    $module = Module::factory()->create();
    $version = ModuleVersion::factory()->for($module)->create();
    $section = ModuleSection::factory()->for($version)->create();
    Question::factory()->for($section)->create();

    $this->actingAs($user)
        ->get(route('admin.questions.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/QuestionEditor')
            ->has('modules', 1)
            ->where('modules.0.id', $module->id)
            ->has('modules.0.versions.0.sections.0.questions', 1)
        );
});

test('a published module version can be copied into a draft', function () {
    $user = questionEditorUser();
    $module = Module::factory()->create();
    $publishedVersion = ModuleVersion::factory()
        ->for($module)
        ->create([
            'status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);
    $section = ModuleSection::factory()->for($publishedVersion)->create();
    $question = Question::factory()
        ->for($section)
        ->create(['question_type' => QuestionType::SingleChoice]);
    QuestionOption::factory()->for($question)->create([
        'option_text' => 'Ja',
        'value' => '1',
    ]);

    $response = $this->actingAs($user)->postJson(
        route('admin.api.module-versions.store'),
        [
            'module_id' => $module->id,
            'source_version_id' => $publishedVersion->id,
        ],
    );

    $response
        ->assertCreated()
        ->assertJsonPath('data.version_number', 2)
        ->assertJsonPath('data.status', 'draft')
        ->assertJsonPath(
            'data.sections.0.questions.0.options.0.option_text',
            'Ja',
        );

    expect($module->versions()->count())->toBe(2);
});

test('questions with choice options can be created and updated in drafts', function () {
    $user = questionEditorUser();
    $version = ModuleVersion::factory()->create([
        'status' => PublicationStatus::Draft,
    ]);
    $section = ModuleSection::factory()->for($version)->create();

    $response = $this->actingAs($user)->postJson(
        route('admin.api.questions.store'),
        [
            'module_section_id' => $section->id,
            'question_text' => 'Welche Lernform bevorzugen Sie?',
            'question_type' => QuestionType::SingleChoice->value,
            'is_required' => true,
            'options' => ['Präsenz', 'Online'],
        ],
    );

    $response
        ->assertCreated()
        ->assertJsonPath('data.options.0.option_text', 'Präsenz')
        ->assertJsonPath('data.options.1.option_text', 'Online');

    $question = Question::query()->firstOrFail();

    $this->actingAs($user)->putJson(
        route('admin.api.questions.update', $question),
        [
            'module_section_id' => $section->id,
            'question_text' => 'Welche Lernformen bevorzugen Sie?',
            'question_type' => QuestionType::FreeText->value,
            'is_required' => false,
            'options' => [],
        ],
    )->assertSuccessful()
        ->assertJsonPath('data.question_type', 'free_text')
        ->assertJsonCount(0, 'data.options');

    expect($question->fresh()->options)->toHaveCount(0);
});

test('draft module versions can be published after questions are added', function () {
    $user = questionEditorUser();
    $version = ModuleVersion::factory()->create([
        'status' => PublicationStatus::Draft,
    ]);
    $section = ModuleSection::factory()->for($version)->create();
    Question::factory()->for($section)->create();

    $this->actingAs($user)
        ->patchJson(route('admin.api.module-versions.publish', $version))
        ->assertSuccessful()
        ->assertJsonPath('data.status', 'published');

    expect($version->fresh()->status)->toBe(PublicationStatus::Published);
});

test('empty draft module versions cannot be published', function () {
    $user = questionEditorUser();
    $version = ModuleVersion::factory()->create([
        'status' => PublicationStatus::Draft,
    ]);

    $this->actingAs($user)
        ->patchJson(route('admin.api.module-versions.publish', $version))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('questions');
});

test('a draft module version ziel and rolle can be updated via the api', function () {
    $user = questionEditorUser();
    $version = ModuleVersion::factory()->create([
        'status' => PublicationStatus::Draft,
        'target_type' => 'none',
        'target_role_id' => null,
    ]);
    $role = TeacherRole::factory()->create(['name' => 'Arbeitspädagoge']);

    $this->actingAs($user)->putJson(
        route('admin.api.module-versions.update', $version),
        [
            'target_type' => 'teacher',
            'target_role_id' => $role->id,
        ],
    )->assertSuccessful()
        ->assertJsonPath('data.target_type', 'teacher')
        ->assertJsonPath('data.target_role_id', $role->id)
        ->assertJsonPath('data.target_role.name', $role->name);

    expect($version->fresh())
        ->target_role_id->toBe($role->id);
});

test('a module version introduction can be updated independently of ziel', function () {
    $user = questionEditorUser();
    $version = ModuleVersion::factory()->create([
        'status' => PublicationStatus::Draft,
        'target_type' => 'teacher',
        'target_role_id' => TeacherRole::factory()->create()->id,
    ]);

    $this->actingAs($user)->putJson(
        route('admin.api.module-versions.update', $version),
        ['description' => 'Willkommen! Bitte antworten Sie ehrlich.'],
    )->assertSuccessful()
        ->assertJsonPath('data.description', 'Willkommen! Bitte antworten Sie ehrlich.');

    expect($version->fresh())
        ->description->toBe('Willkommen! Bitte antworten Sie ehrlich.')
        ->target_type->toBe(\App\Enums\ModuleTargetType::Teacher);
});

test('published versions cannot be changed', function () {
    $user = questionEditorUser();
    $version = ModuleVersion::factory()->create([
        'status' => PublicationStatus::Published,
        'published_at' => now(),
    ]);
    $section = ModuleSection::factory()->for($version)->create();

    $this->actingAs($user)->postJson(
        route('admin.api.questions.store'),
        [
            'module_section_id' => $section->id,
            'question_text' => 'Diese Frage darf nicht gespeichert werden.',
            'question_type' => QuestionType::FreeText->value,
            'is_required' => false,
            'options' => [],
        ],
    )->assertUnprocessable()
        ->assertJsonValidationErrors('module_version_id');
});

test('choice questions require at least two answer options', function () {
    $user = questionEditorUser();
    $version = ModuleVersion::factory()->create();
    $section = ModuleSection::factory()->for($version)->create();

    $this->actingAs($user)->postJson(
        route('admin.api.questions.store'),
        [
            'module_section_id' => $section->id,
            'question_text' => 'Unvollständige Auswahl',
            'question_type' => QuestionType::SingleChoice->value,
            'options' => ['Nur eine Option'],
        ],
    )->assertUnprocessable()
        ->assertJsonValidationErrors('options');
});
