<?php

use App\Enums\OrganizationUnitType;
use App\Enums\QuestionType;
use App\Models\Course;
use App\Models\EvaluationCampaignTarget;
use App\Models\OrganizationUnit;
use App\Models\Question;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

test('creates the evaluation platform tables', function () {
    $tables = [
        'organization_units',
        'roles',
        'permissions',
        'role_permission',
        'user_role',
        'user_permission',
        'courses',
        'teachers',
        'course_teacher',
        'questionnaire_templates',
        'questionnaire_versions',
        'modules',
        'module_versions',
        'questionnaire_version_modules',
        'module_sections',
        'questions',
        'question_options',
        'evaluation_campaigns',
        'evaluation_campaign_targets',
        'tans',
        'responses',
        'response_answers',
        'report_templates',
        'report_template_sections',
        'report_runs',
        'benchmark_groups',
        'benchmark_snapshots',
        'email_templates',
        'email_jobs',
        'email_recipients',
        'email_logs',
    ];

    foreach ($tables as $table) {
        expect(Schema::hasTable($table))->toBeTrue();
    }

    expect(Schema::hasColumns('users', ['organization_unit_id', 'is_active']))
        ->toBeTrue()
        ->and(Schema::hasColumns('responses', [
            'evaluation_campaign_id',
            'tan_id',
            'submitted_at',
            'ip_hash',
        ]))->toBeTrue();
});

test('enforces course codes within an organization unit', function () {
    $organizationUnit = OrganizationUnit::factory()->create();

    Course::factory()->for($organizationUnit)->create(['code' => 'CS-101']);

    expect(fn () => Course::factory()->for($organizationUnit)->create([
        'code' => 'CS-101',
    ]))->toThrow(QueryException::class);
});

test('casts domain values and resolves campaign target morphs', function () {
    $organizationUnit = OrganizationUnit::factory()->create([
        'type' => OrganizationUnitType::Department,
    ]);
    $teacher = Teacher::factory()->for($organizationUnit)->create();
    $question = Question::factory()->create([
        'question_type' => QuestionType::FreeText,
    ]);

    $target = new EvaluationCampaignTarget([
        'target_type' => 'teacher',
        'target_id' => $teacher->id,
    ]);

    expect($organizationUnit->type)->toBe(OrganizationUnitType::Department)
        ->and($question->question_type)->toBe(QuestionType::FreeText)
        ->and($target->target()->getModel())->toBeInstanceOf(Teacher::class);
});

test('seeds real organization units and super administrator', function () {
    $this->seed(DatabaseSeeder::class);

    $organizationUnits = [
        'Berlin' => ['Berlin PrenzlauerPromenade', 'Berlin Trachenberg'],
        'Sachsen' => ['Chemnitz', 'Döbeln', 'Dresden', 'Leipzig', 'Riesa'],
        'Thüringen' => ['Erfurt', 'Suhl'],
    ];

    foreach ($organizationUnits as $parentName => $childNames) {
        $parent = OrganizationUnit::query()
            ->where('name', $parentName)
            ->whereNull('parent_id')
            ->firstOrFail();

        expect($parent->type)->toBe(OrganizationUnitType::Institution)
            ->and($parent->is_active)->toBeTrue();

        foreach ($childNames as $sortOrder => $childName) {
            $this->assertDatabaseHas('organization_units', [
                'parent_id' => $parent->id,
                'name' => $childName,
                'type' => OrganizationUnitType::Department->value,
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        }
    }

    $user = User::query()->where('email', 'ara.matoyan@miqr.de')->firstOrFail();

    expect($user->name)->toBe('Ara')
        ->and($user->organization_unit_id)->toBeNull()
        ->and($user->is_active)->toBeTrue()
        ->and(Hash::check('123qwe!', $user->password))->toBeTrue()
        ->and($user->hasRole('super-admin'))->toBeTrue();

    $this->assertDatabaseMissing('users', ['email' => 'admin@example.com']);
    $this->assertDatabaseMissing('courses', ['code' => 'CS-101']);
    $this->assertDatabaseMissing('evaluation_campaigns', [
        'title' => 'CS-101 Course Evaluation',
    ]);
});
