<?php

use App\Enums\EvaluationCampaignStatus;
use App\Enums\ModuleTargetType;
use App\Enums\PublicationStatus;
use App\Enums\QuestionType;
use App\Enums\RepeatMode;
use App\Models\Course;
use App\Models\EvaluationCampaign;
use App\Models\Module;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use App\Models\OrganizationUnit;
use App\Models\Question;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\QuestionnaireVersionModule;
use App\Models\SurveyResponse;
use App\Models\Tan;
use App\Models\Teacher;
use App\Services\TanService;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

test('participants complete an anonymous tan based evaluation', function () {
    $organizationUnit = OrganizationUnit::factory()->create(['name' => 'Erfurt']);
    $course = Course::factory()->for($organizationUnit)->create([
        'name' => 'Deutsch B2',
    ]);
    $firstTeacher = Teacher::factory()->for($organizationUnit)->create([
        'name' => 'Frau Müller',
    ]);
    $secondTeacher = Teacher::factory()->for($organizationUnit)->create([
        'name' => 'Herr Schmidt',
    ]);
    $course->teachers()->attach([$firstTeacher->id, $secondTeacher->id]);

    $organizationQuestion = createPublishedQuestion(
        moduleName: 'Organisation',
        targetType: ModuleTargetType::None,
        questionText: 'Die Organisation war gut vorbereitet.',
    );
    $teacherQuestion = createPublishedQuestion(
        moduleName: 'Dozent / Unterricht',
        targetType: ModuleTargetType::Teacher,
        questionText: 'Der Dozent erklärt verständlich.',
    );

    $template = QuestionnaireTemplate::factory()->create(['name' => 'Kursbewertung']);
    $questionnaireVersion = QuestionnaireVersion::factory()
        ->for($template)
        ->create([
            'title' => 'Kursbewertung',
            'status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);
    QuestionnaireVersionModule::query()->create([
        'questionnaire_version_id' => $questionnaireVersion->id,
        'module_version_id' => $organizationQuestion->moduleSection->moduleVersion->id,
        'sort_order' => 0,
        'repeat_mode' => RepeatMode::Once,
    ]);
    QuestionnaireVersionModule::query()->create([
        'questionnaire_version_id' => $questionnaireVersion->id,
        'module_version_id' => $teacherQuestion->moduleSection->moduleVersion->id,
        'sort_order' => 1,
        'repeat_mode' => RepeatMode::PerTarget,
    ]);

    $campaign = EvaluationCampaign::factory()
        ->for($organizationUnit)
        ->for($course)
        ->for($questionnaireVersion)
        ->create([
            'title' => 'Deutsch B2 Kursbewertung September 2026',
            'status' => EvaluationCampaignStatus::Active,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);
    $campaign->targets()->create([
        'target_type' => 'organization',
        'target_id' => $organizationUnit->id,
        'label' => 'Erfurt',
        'sort_order' => 0,
    ]);
    $campaign->targets()->create([
        'target_type' => 'course',
        'target_id' => $course->id,
        'label' => 'Deutsch B2',
        'sort_order' => 1,
    ]);
    $firstTeacherTarget = $campaign->targets()->create([
        'target_type' => 'teacher',
        'target_id' => $firstTeacher->id,
        'label' => 'Frau Müller',
        'sort_order' => 2,
    ]);
    $secondTeacherTarget = $campaign->targets()->create([
        'target_type' => 'teacher',
        'target_id' => $secondTeacher->id,
        'label' => 'Herr Schmidt',
        'sort_order' => 3,
    ]);

    $tan = Tan::query()->create([
        'evaluation_campaign_id' => $campaign->id,
        'tan_code_hash' => app(TanService::class)->hash('A8K9-PQ22'),
        'is_active' => true,
    ]);

    $this->get(route('evaluation.tan.create'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('evaluation/EnterTan')
        );

    $tanResponse = $this->post(route('evaluation.tan.store'), [
        'tan_code' => 'A8K9-PQ22',
    ]);
    $tanResponse->assertRedirect();

    $formUrl = $tanResponse->headers->get('Location');
    expect($formUrl)->toContain('/evaluation/form/');
    $session = Str::afterLast($formUrl, '/');

    $this->get($formUrl)
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('evaluation/Form')
            ->where('form.campaign.title', 'Deutsch B2 Kursbewertung September 2026')
            ->has('form.modules', 3)
            ->where('form.modules.0.title', 'Organisation')
            ->where('form.modules.1.title', 'Dozent / Unterricht')
            ->where('form.modules.1.target.label', 'Frau Müller')
            ->where('form.modules.2.target.label', 'Herr Schmidt')
        );

    $this->post(route('evaluation.form.store', $session), [
        'language' => 'de',
        'answers' => [
            "q{$organizationQuestion->id}" => 5,
            "q{$teacherQuestion->id}_t{$firstTeacherTarget->id}" => 4,
            "q{$teacherQuestion->id}_t{$secondTeacherTarget->id}" => 3,
        ],
    ])->assertRedirect(route('evaluation.finished'));

    expect($tan->fresh()->used_at)->not->toBeNull()
        ->and(SurveyResponse::query()->count())->toBe(1);

    $response = SurveyResponse::query()->with('answers')->firstOrFail();

    expect($response->answers)->toHaveCount(3)
        ->and($response->answers->pluck('evaluation_campaign_target_id')->all())
        ->toContain($firstTeacherTarget->id, $secondTeacherTarget->id);
});

function createPublishedQuestion(
    string $moduleName,
    ModuleTargetType $targetType,
    string $questionText,
): Question {
    $module = Module::factory()->create(['name' => $moduleName]);
    $moduleVersion = ModuleVersion::factory()
        ->for($module)
        ->create([
            'title' => $moduleName,
            'status' => PublicationStatus::Published,
            'target_type' => $targetType,
            'published_at' => now(),
        ]);
    $section = ModuleSection::factory()
        ->for($moduleVersion)
        ->create(['title' => 'Bewertung']);

    return Question::factory()
        ->for($section)
        ->create([
            'question_text' => $questionText,
            'question_type' => QuestionType::Scale,
            'scale_min' => 1,
            'scale_max' => 5,
        ]);
}
