<?php

use App\Enums\ModuleTargetType;
use App\Enums\PublicationStatus;
use App\Enums\QuestionType;
use App\Enums\RepeatMode;
use App\Models\Course;
use App\Models\EvaluationCampaign;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use App\Models\Question;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\QuestionnaireVersionModule;
use App\Models\Subject;
use App\Models\Tan;
use App\Models\Teacher;
use App\Services\CourseTeacherAssignmentSyncService;
use App\Services\EvaluationCampaignTargetSyncService;
use App\Services\QuestionnaireRenderService;
use App\Services\ResponseSubmissionService;
use App\Services\TanService;

test('a teacher teaching two subjects in one course gets two separate campaign targets', function () {
    $course = Course::factory()->create();
    $teacher = Teacher::factory()->create();
    $materials = Subject::factory()->create(['name' => 'Werkstoffwirtschaft']);
    $office = Subject::factory()->create(['name' => 'Büroorganisation']);

    app(CourseTeacherAssignmentSyncService::class)->syncForCourse($course, [
        ['teacher_id' => $teacher->id, 'subject_id' => $materials->id],
        ['teacher_id' => $teacher->id, 'subject_id' => $office->id],
    ]);

    $campaign = EvaluationCampaign::factory()->create(['course_id' => $course->id]);

    app(EvaluationCampaignTargetSyncService::class)->syncCourseTeachers($campaign);

    $targets = $campaign->targets()->get();

    expect($targets)->toHaveCount(2);
    expect($targets->pluck('target_id')->map(fn ($value) => (int) $value)->unique()->all())
        ->toBe([$teacher->id]);
    expect($targets->pluck('subject_id')->map(fn ($value) => (int) $value)->sort()->values()->all())
        ->toBe(collect([$materials->id, $office->id])->sort()->values()->all());
    expect($targets->pluck('label')->sort()->values()->all())
        ->toBe(collect([
            "{$teacher->name} – {$materials->name}",
            "{$teacher->name} – {$office->name}",
        ])->sort()->values()->all());
});

test('removing a subject assignment drops only that target on re-sync', function () {
    $course = Course::factory()->create();
    $teacher = Teacher::factory()->create();
    $materials = Subject::factory()->create();
    $office = Subject::factory()->create();

    $assignmentSyncService = app(CourseTeacherAssignmentSyncService::class);
    $targetSyncService = app(EvaluationCampaignTargetSyncService::class);

    $assignmentSyncService->syncForCourse($course, [
        ['teacher_id' => $teacher->id, 'subject_id' => $materials->id],
        ['teacher_id' => $teacher->id, 'subject_id' => $office->id],
    ]);

    $campaign = EvaluationCampaign::factory()->create(['course_id' => $course->id]);
    $targetSyncService->syncCourseTeachers($campaign);

    expect($campaign->targets()->count())->toBe(2);

    $assignmentSyncService->syncForCourse($course, [
        ['teacher_id' => $teacher->id, 'subject_id' => $materials->id],
    ]);
    $targetSyncService->syncCourseTeachers($campaign);

    $remaining = $campaign->targets()->get();
    expect($remaining)->toHaveCount(1);
    expect((int) $remaining->first()->subject_id)->toBe($materials->id);
});

test('a teacher with a single, subject-less assignment still gets exactly one target', function () {
    $course = Course::factory()->create();
    $teacher = Teacher::factory()->create();

    app(CourseTeacherAssignmentSyncService::class)->syncForCourse($course, [
        ['teacher_id' => $teacher->id, 'subject_id' => null],
    ]);

    $campaign = EvaluationCampaign::factory()->create(['course_id' => $course->id]);
    app(EvaluationCampaignTargetSyncService::class)->syncCourseTeachers($campaign);

    $targets = $campaign->targets()->get();

    expect($targets)->toHaveCount(1);
    expect($targets->first()->subject_id)->toBeNull();
    expect($targets->first()->label)->toBe($teacher->name);
});

test('the questionnaire renders and accepts independent answers for each subject target', function () {
    $course = Course::factory()->create();
    $teacher = Teacher::factory()->create();
    $materials = Subject::factory()->create(['name' => 'Werkstoffwirtschaft']);
    $office = Subject::factory()->create(['name' => 'Büroorganisation']);

    app(CourseTeacherAssignmentSyncService::class)->syncForCourse($course, [
        ['teacher_id' => $teacher->id, 'subject_id' => $materials->id],
        ['teacher_id' => $teacher->id, 'subject_id' => $office->id],
    ]);

    $moduleVersion = ModuleVersion::factory()->create([
        'target_type' => ModuleTargetType::Teacher,
        'status' => PublicationStatus::Published,
    ]);
    $section = ModuleSection::factory()->for($moduleVersion)->create();
    Question::factory()->for($section, 'moduleSection')->create([
        'question_type' => QuestionType::Scale,
        'scale_min' => 1,
        'scale_max' => 5,
        'is_required' => true,
    ]);

    $questionnaireVersion = QuestionnaireVersion::factory()
        ->for(QuestionnaireTemplate::factory()->create())
        ->create(['status' => PublicationStatus::Published]);

    QuestionnaireVersionModule::query()->create([
        'questionnaire_version_id' => $questionnaireVersion->id,
        'module_version_id' => $moduleVersion->id,
        'sort_order' => 0,
        'repeat_mode' => RepeatMode::PerTarget,
    ]);

    $campaign = EvaluationCampaign::factory()->create([
        'course_id' => $course->id,
        'questionnaire_version_id' => $questionnaireVersion->id,
    ]);
    app(EvaluationCampaignTargetSyncService::class)->syncCourseTeachers($campaign);

    $rendered = app(QuestionnaireRenderService::class)->render($campaign->fresh());

    expect($rendered['modules'])->toHaveCount(2);

    $answerKeys = collect($rendered['modules'])
        ->flatMap(fn (array $module) => $module['sections'])
        ->flatMap(fn (array $section) => $section['questions'])
        ->pluck('answer_key')
        ->values();

    expect($answerKeys)->toHaveCount(2);
    expect($answerKeys->unique())->toHaveCount(2);

    $tan = Tan::query()->create([
        'evaluation_campaign_id' => $campaign->id,
        'tan_code_hash' => app(TanService::class)->hash('A8K9-PQ22'),
        'is_active' => true,
    ]);

    $answers = $answerKeys->mapWithKeys(fn (string $key, int $index) => [$key => $index + 3])->all();

    $response = app(ResponseSubmissionService::class)->submit(
        $tan,
        $answers,
        'de',
        null,
        null,
    );

    expect($response->answers)->toHaveCount(2);
    expect($response->answers->pluck('evaluation_campaign_target_id')->unique())->toHaveCount(2);
});
