<?php

namespace Database\Seeders;

use App\Enums\EvaluationCampaignStatus;
use App\Enums\ModuleTargetType;
use App\Enums\PublicationStatus;
use App\Enums\QuestionType;
use App\Enums\RepeatMode;
use App\Models\Course;
use App\Models\EvaluationCampaign;
use App\Models\EvaluationCampaignTarget;
use App\Models\Module;
use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use App\Models\OrganizationUnit;
use App\Models\Question;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\QuestionnaireVersionModule;
use App\Models\Teacher;
use App\Models\User;
use App\Services\QuestionnaireRenderService;
use App\Services\ResponseSubmissionService;
use App\Services\TanGenerationService;
use App\Services\TanService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Development-only demo data: courses, a reusable questionnaire, and
 * evaluation campaigns spread across different time periods with
 * realistic answers, so reporting can be designed against real-looking
 * data instead of an empty database.
 */
class DemoEvaluationSeeder extends Seeder
{
    use WithoutModelEvents;

    private const SCALE_POOL = [5, 5, 4, 4, 4, 3, 3, 2, 1];

    private const COMMENT_POOL = [
        'Insgesamt eine runde Sache, danke.',
        'Mehr Praxisbeispiele wären hilfreich gewesen.',
        'Der Zeitplan war stellenweise sehr eng.',
        'Sehr gute Betreuung während des gesamten Kurses.',
        'Die Räume könnten besser belüftet sein.',
        null,
        null,
    ];

    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command->warn(
                'Skipping DemoEvaluationSeeder: only runs in local/testing environments.',
            );

            return;
        }

        $creator = User::query()->orderBy('id')->firstOrFail();

        $teachers = $this->seedTeachers();
        $courses = $this->seedCourses($teachers);
        $modules = $this->seedModules($creator);
        $this->seedDraftModuleVersion($creator, $modules['communication']);

        $questionnaireVersion = $this->seedQuestionnaire(
            $creator,
            $modules['organization'],
            $modules['teacher'],
            $modules['facility'],
        );
        $this->seedDraftQuestionnaire(
            $creator,
            $modules['organization'],
            $modules['teacher'],
            $modules['communication'],
        );

        $now = CarbonImmutable::now();

        $this->seedCampaign(
            course: $courses['erfurt'],
            teacher: $teachers['erfurt'],
            questionnaireVersion: $questionnaireVersion,
            creator: $creator,
            title: 'Integration Erfurt – Herbstdurchgang',
            starts: $now->subMonths(4),
            ends: $now->subMonths(3)->subWeek(),
            finalStatus: EvaluationCampaignStatus::Archived,
            tanCount: 16,
            responseCount: 14,
        );

        $this->seedCampaign(
            course: $courses['suhl'],
            teacher: $teachers['suhl'],
            questionnaireVersion: $questionnaireVersion,
            creator: $creator,
            title: 'Umschulung Suhl – Winterdurchgang',
            starts: $now->subMonths(2)->subWeeks(2),
            ends: $now->subMonths(2),
            finalStatus: EvaluationCampaignStatus::Closed,
            tanCount: 20,
            responseCount: 18,
        );

        $this->seedCampaign(
            course: $courses['leipzig'],
            teacher: $teachers['leipzig'],
            questionnaireVersion: $questionnaireVersion,
            creator: $creator,
            title: 'Integration Leipzig – Winterdurchgang',
            starts: $now->subWeeks(6),
            ends: $now->subWeeks(3),
            finalStatus: EvaluationCampaignStatus::Closed,
            tanCount: 13,
            responseCount: 11,
        );

        $this->seedCampaign(
            course: $courses['chemnitz'],
            teacher: $teachers['chemnitz'],
            questionnaireVersion: $questionnaireVersion,
            creator: $creator,
            title: 'Umschulung Chemnitz – laufender Durchgang',
            starts: $now->subWeeks(2),
            ends: $now->addWeeks(2),
            finalStatus: EvaluationCampaignStatus::Active,
            tanCount: 10,
            responseCount: 7,
        );

        $this->seedCampaign(
            course: $courses['doebeln'],
            teacher: $teachers['doebeln'],
            questionnaireVersion: $questionnaireVersion,
            creator: $creator,
            title: 'Erprobung Döbeln – bevorstehender Durchgang',
            starts: $now->addWeek(),
            ends: $now->addWeeks(5),
            finalStatus: EvaluationCampaignStatus::Scheduled,
            tanCount: 6,
            responseCount: 0,
        );
    }

    /**
     * @return array<string, Teacher>
     */
    private function seedTeachers(): array
    {
        $units = $this->organizationUnits();
        $definitions = [
            'erfurt' => ['name' => 'Maria Keller', 'unit' => 'Erfurt'],
            'suhl' => ['name' => 'Jonas Weber', 'unit' => 'Suhl'],
            'leipzig' => ['name' => 'Sophie Fischer', 'unit' => 'Leipzig'],
            'chemnitz' => ['name' => 'Lukas Hoffmann', 'unit' => 'Chemnitz'],
            'doebeln' => ['name' => 'Elena Schröder', 'unit' => 'Döbeln'],
        ];

        $teachers = [];

        foreach ($definitions as $key => $definition) {
            $teachers[$key] = Teacher::query()->updateOrCreate(
                ['email' => Str::of($definition['name'])->slug().'@eva-demo.de'],
                [
                    'name' => $definition['name'],
                    'organization_unit_id' => $units[$definition['unit']]->id,
                    'is_active' => true,
                ],
            );
        }

        return $teachers;
    }

    /**
     * @param  array<string, Teacher>  $teachers
     * @return array<string, Course>
     */
    private function seedCourses(array $teachers): array
    {
        $units = $this->organizationUnits();
        $definitions = [
            'erfurt' => ['name' => 'Erfurt - Integration', 'code' => 'ERF-INT', 'unit' => 'Erfurt', 'teacher' => 'erfurt'],
            'suhl' => ['name' => 'Suhl - Umschulung', 'code' => 'SUH-UMS', 'unit' => 'Suhl', 'teacher' => 'suhl'],
            'leipzig' => ['name' => 'Leipzig - Integration', 'code' => 'LEI-INT', 'unit' => 'Leipzig', 'teacher' => 'leipzig'],
            'chemnitz' => ['name' => 'Chemnitz - Umschulung', 'code' => 'CHE-UMS', 'unit' => 'Chemnitz', 'teacher' => 'chemnitz'],
            'doebeln' => ['name' => 'Döbeln - Erprobung', 'code' => 'DOE-ERP', 'unit' => 'Döbeln', 'teacher' => 'doebeln'],
        ];

        $courses = [];

        foreach ($definitions as $key => $definition) {
            $course = Course::query()->updateOrCreate(
                [
                    'organization_unit_id' => $units[$definition['unit']]->id,
                    'code' => $definition['code'],
                ],
                [
                    'name' => $definition['name'],
                    'starts_at' => CarbonImmutable::now()->subMonths(6),
                    'ends_at' => CarbonImmutable::now()->addMonths(6),
                    'is_active' => true,
                ],
            );

            $course->teachers()->syncWithoutDetaching([
                $teachers[$definition['teacher']]->id,
            ]);

            $courses[$key] = $course;
        }

        return $courses;
    }

    /**
     * @return array<string, OrganizationUnit>
     */
    private function organizationUnits(): array
    {
        return OrganizationUnit::query()
            ->whereIn('name', ['Erfurt', 'Suhl', 'Leipzig', 'Chemnitz', 'Döbeln'])
            ->get()
            ->keyBy('name')
            ->all();
    }

    /**
     * @return array<string, Module>
     */
    private function seedModules(User $creator): array
    {
        $organizationModule = $this->seedModule(
            $creator,
            'Kursorganisation',
            'Zufriedenheit mit Ablauf und Organisation des Kurses.',
            ModuleTargetType::None,
            [
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie zufrieden waren Sie insgesamt mit der Organisation des Kurses?',
                    'min_label' => 'Sehr unzufrieden',
                    'max_label' => 'Sehr zufrieden',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie verständlich war der vermittelte Lernstoff?',
                    'min_label' => 'Gar nicht verständlich',
                    'max_label' => 'Sehr verständlich',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::SingleChoice,
                    'text' => 'Wie haben Sie von diesem Kurs erfahren?',
                    'options' => ['Agentur für Arbeit', 'Empfehlung', 'Internet', 'Sonstiges'],
                    'required' => true,
                ],
                [
                    'type' => QuestionType::FreeText,
                    'text' => 'Was können wir am Kurs verbessern?',
                    'required' => false,
                ],
            ],
        );

        $teacherModule = $this->seedModule(
            $creator,
            'Dozentenbewertung',
            'Bewertung der Lehrperson je Kurs.',
            ModuleTargetType::Teacher,
            [
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie bewerten Sie die fachliche Kompetenz der Lehrperson?',
                    'min_label' => 'Sehr schlecht',
                    'max_label' => 'Sehr gut',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie bewerten Sie die didaktische Vermittlung?',
                    'min_label' => 'Sehr schlecht',
                    'max_label' => 'Sehr gut',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::YesNo,
                    'text' => 'Würden Sie diese Lehrperson weiterempfehlen?',
                    'required' => true,
                ],
            ],
        );

        $facilityModule = $this->seedModule(
            $creator,
            'Ausstattung',
            'Bewertung der räumlichen und technischen Ausstattung.',
            ModuleTargetType::None,
            [
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie bewerten Sie die technische Ausstattung der Räume?',
                    'min_label' => 'Sehr schlecht',
                    'max_label' => 'Sehr gut',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::YesNo,
                    'text' => 'War die Ausstattung ausreichend für den Kurs?',
                    'required' => false,
                ],
            ],
        );

        $communicationModule = $this->seedModule(
            $creator,
            'Kommunikation',
            'Qualität der Kommunikation während des Kurses.',
            ModuleTargetType::None,
            [
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie bewerten Sie die Kommunikation innerhalb der Gruppe?',
                    'min_label' => 'Sehr schlecht',
                    'max_label' => 'Sehr gut',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie gut wurden Rückfragen beantwortet?',
                    'min_label' => 'Sehr schlecht',
                    'max_label' => 'Sehr gut',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::FreeText,
                    'text' => 'Anmerkungen zur Kommunikation?',
                    'required' => false,
                ],
            ],
        );

        $sustainabilityModule = $this->seedModule(
            $creator,
            'Nachhaltigkeit',
            'Langfristiger Nutzen der vermittelten Inhalte.',
            ModuleTargetType::None,
            [
                [
                    'type' => QuestionType::YesNo,
                    'text' => 'Konnten Sie die erlernten Inhalte bereits anwenden?',
                    'required' => true,
                ],
                [
                    'type' => QuestionType::Scale,
                    'text' => 'Wie hoch schätzen Sie den langfristigen Nutzen dieses Kurses ein?',
                    'min_label' => 'Sehr gering',
                    'max_label' => 'Sehr hoch',
                    'required' => true,
                ],
            ],
        );

        return [
            'organization' => $organizationModule,
            'teacher' => $teacherModule,
            'facility' => $facilityModule,
            'communication' => $communicationModule,
            'sustainability' => $sustainabilityModule,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $questions
     */
    private function seedModule(
        User $creator,
        string $name,
        string $description,
        ModuleTargetType $targetType,
        array $questions,
    ): Module {
        $module = Module::query()->updateOrCreate(
            ['name' => $name],
            [
                'description' => $description,
                'is_active' => true,
                'created_by_id' => $creator->id,
            ],
        );

        $version = ModuleVersion::query()->firstOrCreate(
            ['module_id' => $module->id, 'version_number' => 1],
            [
                'title' => $name,
                'description' => $description,
                'status' => PublicationStatus::Published,
                'default_language' => 'de',
                'target_type' => $targetType,
                'created_by_id' => $creator->id,
                'published_at' => CarbonImmutable::now()->subMonths(6),
            ],
        );

        if (! $version->wasRecentlyCreated) {
            return $module;
        }

        $this->createSectionWithQuestions($version, $name, $questions);

        return $module;
    }

    /**
     * Adds a second, still-editable draft version to a module, so the
     * Frageneditor has an in-progress version to demo alongside the
     * published one, instead of everything being read-only.
     */
    private function seedDraftModuleVersion(User $creator, Module $module): void
    {
        $version = ModuleVersion::query()->firstOrCreate(
            ['module_id' => $module->id, 'version_number' => 2],
            [
                'title' => "{$module->name} (überarbeitet)",
                'description' => $module->description,
                'status' => PublicationStatus::Draft,
                'default_language' => 'de',
                'target_type' => ModuleTargetType::None,
                'created_by_id' => $creator->id,
                'published_at' => null,
            ],
        );

        if (! $version->wasRecentlyCreated) {
            return;
        }

        $this->createSectionWithQuestions($version, $module->name, [
            [
                'type' => QuestionType::Scale,
                'text' => 'Wie bewerten Sie die Kommunikation innerhalb der Gruppe?',
                'min_label' => 'Sehr schlecht',
                'max_label' => 'Sehr gut',
                'required' => true,
            ],
            [
                'type' => QuestionType::Scale,
                'text' => 'Wie gut wurden Rückfragen beantwortet?',
                'min_label' => 'Sehr schlecht',
                'max_label' => 'Sehr gut',
                'required' => true,
            ],
            [
                'type' => QuestionType::Scale,
                'text' => 'Wie bewerten Sie die Kommunikation außerhalb der Kurszeiten (z. B. E-Mail)?',
                'min_label' => 'Sehr schlecht',
                'max_label' => 'Sehr gut',
                'required' => false,
            ],
            [
                'type' => QuestionType::FreeText,
                'text' => 'Anmerkungen zur Kommunikation?',
                'required' => false,
            ],
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $questions
     */
    private function createSectionWithQuestions(
        ModuleVersion $version,
        string $sectionTitle,
        array $questions,
    ): void {
        $section = ModuleSection::query()->create([
            'module_version_id' => $version->id,
            'title' => $sectionTitle,
            'description' => null,
            'sort_order' => 0,
        ]);

        foreach ($questions as $index => $definition) {
            $isScale = $definition['type'] === QuestionType::Scale;

            $question = Question::query()->create([
                'module_section_id' => $section->id,
                'question_text' => $definition['text'],
                'question_type' => $definition['type'],
                'scale_min' => $isScale ? 1 : null,
                'scale_max' => $isScale ? 5 : null,
                'scale_min_label' => $isScale ? $definition['min_label'] : null,
                'scale_max_label' => $isScale ? $definition['max_label'] : null,
                'is_required' => $definition['required'],
                'sort_order' => $index,
            ]);

            if ($definition['type'] !== QuestionType::SingleChoice) {
                continue;
            }

            foreach ($definition['options'] as $optionIndex => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'value' => (string) ($optionIndex + 1),
                    'sort_order' => $optionIndex,
                ]);
            }
        }
    }

    private function seedQuestionnaire(
        User $creator,
        Module $organizationModule,
        Module $teacherModule,
        Module $facilityModule,
    ): QuestionnaireVersion {
        $template = QuestionnaireTemplate::query()->updateOrCreate(
            ['name' => 'Standard-Kursevaluation'],
            [
                'description' => 'Wiederverwendbare Standardevaluation für Kurse mit Dozenten- und Ausstattungsbewertung.',
                'is_active' => true,
                'created_by_id' => $creator->id,
            ],
        );

        $version = QuestionnaireVersion::query()->firstOrCreate(
            ['questionnaire_template_id' => $template->id, 'version_number' => 1],
            [
                'title' => $template->name,
                'description' => $template->description,
                'status' => PublicationStatus::Published,
                'default_language' => 'de',
                'min_answers_to_show_results' => 5,
                'created_by_id' => $creator->id,
                'published_at' => CarbonImmutable::now()->subMonths(6),
            ],
        );

        if (! $version->wasRecentlyCreated) {
            return $version;
        }

        $links = [
            [$organizationModule, RepeatMode::Once],
            [$teacherModule, RepeatMode::PerTarget],
            [$facilityModule, RepeatMode::Once],
        ];

        foreach ($links as $sortOrder => [$module, $repeatMode]) {
            QuestionnaireVersionModule::query()->create([
                'questionnaire_version_id' => $version->id,
                'module_version_id' => $this->publishedModuleVersionId($module),
                'sort_order' => $sortOrder,
                'repeat_mode' => $repeatMode,
            ]);
        }

        return $version;
    }

    /**
     * Adds a second questionnaire template that is intentionally left as
     * an in-progress draft with only some modules linked, so the
     * Fragebogen-Builder has a realistic work-in-progress to demo the
     * "add module" and "publish" flow against.
     */
    private function seedDraftQuestionnaire(
        User $creator,
        Module $organizationModule,
        Module $teacherModule,
        Module $communicationModule,
    ): void {
        $template = QuestionnaireTemplate::query()->updateOrCreate(
            ['name' => 'Erweiterte Kursevaluation'],
            [
                'description' => 'Entwurf für eine erweiterte Evaluation mit zusätzlichem Kommunikations-Modul.',
                'is_active' => true,
                'created_by_id' => $creator->id,
            ],
        );

        $version = QuestionnaireVersion::query()->firstOrCreate(
            ['questionnaire_template_id' => $template->id, 'version_number' => 1],
            [
                'title' => $template->name,
                'description' => $template->description,
                'status' => PublicationStatus::Draft,
                'default_language' => 'de',
                'min_answers_to_show_results' => 5,
                'created_by_id' => $creator->id,
                'published_at' => null,
            ],
        );

        if (! $version->wasRecentlyCreated) {
            return;
        }

        $links = [
            [$organizationModule, RepeatMode::Once],
            [$communicationModule, RepeatMode::Once],
            [$teacherModule, RepeatMode::PerTarget],
        ];

        foreach ($links as $sortOrder => [$module, $repeatMode]) {
            QuestionnaireVersionModule::query()->create([
                'questionnaire_version_id' => $version->id,
                'module_version_id' => $this->publishedModuleVersionId($module),
                'sort_order' => $sortOrder,
                'repeat_mode' => $repeatMode,
            ]);
        }
    }

    private function publishedModuleVersionId(Module $module): int
    {
        return $module->versions()
            ->where('status', PublicationStatus::Published)
            ->orderByDesc('version_number')
            ->firstOrFail()
            ->id;
    }

    private function seedCampaign(
        Course $course,
        Teacher $teacher,
        QuestionnaireVersion $questionnaireVersion,
        User $creator,
        string $title,
        CarbonImmutable $starts,
        CarbonImmutable $ends,
        EvaluationCampaignStatus $finalStatus,
        int $tanCount,
        int $responseCount,
    ): void {
        $campaign = EvaluationCampaign::query()->updateOrCreate(
            ['title' => $title],
            [
                'organization_unit_id' => $course->organization_unit_id,
                'course_id' => $course->id,
                'questionnaire_version_id' => $questionnaireVersion->id,
                'description' => "Automatisch erzeugte Demo-Evaluation für {$course->name}.",
                'starts_at' => $starts,
                'ends_at' => $ends,
                'status' => EvaluationCampaignStatus::Active,
                'min_answers_to_show_results' => 5,
                'created_by_id' => $creator->id,
            ],
        );

        if ($campaign->tans()->exists()) {
            $campaign->update(['status' => $finalStatus]);

            return;
        }

        EvaluationCampaignTarget::query()->updateOrCreate(
            [
                'evaluation_campaign_id' => $campaign->id,
                'target_type' => 'teacher',
                'target_id' => $teacher->id,
            ],
            ['label' => $teacher->name, 'sort_order' => 0],
        );

        $tanService = app(TanService::class);
        $tanGenerationService = app(TanGenerationService::class);
        $responseSubmissionService = app(ResponseSubmissionService::class);
        $renderService = app(QuestionnaireRenderService::class);

        $codes = $tanGenerationService->generate($campaign->fresh(), $tanCount);
        $tans = $campaign->tans()
            ->whereIn('tan_code_hash', array_map(
                fn (string $code): string => $tanService->hash($code),
                $codes,
            ))
            ->get()
            ->values();

        DB::transaction(function () use (
            $campaign,
            $tans,
            $responseCount,
            $starts,
            $ends,
            $responseSubmissionService,
            $renderService,
        ): void {
            $windowEnd = $ends->min(CarbonImmutable::now());

            for ($index = 0; $index < $responseCount; $index++) {
                $submittedAt = $this->randomMoment($starts, $windowEnd);
                $tan = $tans[$index];

                Date::setTestNow($submittedAt->subMinutes(random_int(4, 25)));
                $tan->forceFill(['started_at' => Date::now()])->save();

                Date::setTestNow($submittedAt);

                try {
                    $rendered = $renderService->render($campaign->fresh());
                    $answers = $this->randomAnswers($rendered);

                    $responseSubmissionService->submit(
                        $tan->fresh(),
                        $answers,
                        'de',
                        'DemoEvaluationSeeder',
                        null,
                    );
                } finally {
                    Date::setTestNow();
                }
            }
        });

        $campaign->update(['status' => $finalStatus]);
    }

    /**
     * @param  array<string, mixed>  $renderedForm
     * @return array<string, mixed>
     */
    private function randomAnswers(array $renderedForm): array
    {
        $answers = [];

        foreach ($renderedForm['modules'] as $module) {
            foreach ($module['sections'] as $section) {
                foreach ($section['questions'] as $question) {
                    $answers[$question['answer_key']] = $this->randomAnswerFor($question);
                }
            }
        }

        return $answers;
    }

    /**
     * @param  array<string, mixed>  $question
     */
    private function randomAnswerFor(array $question): mixed
    {
        return match ($question['question_type']) {
            'scale' => self::SCALE_POOL[array_rand(self::SCALE_POOL)],
            'yes_no' => random_int(0, 4) === 0 ? 'no' : 'yes',
            'single_choice' => $question['options'][array_rand($question['options'])]['id'],
            'free_text' => self::COMMENT_POOL[array_rand(self::COMMENT_POOL)],
            default => null,
        };
    }

    private function randomMoment(CarbonImmutable $start, CarbonImmutable $end): CarbonImmutable
    {
        $startTimestamp = $start->getTimestamp();
        $endTimestamp = max($startTimestamp + 60, $end->getTimestamp());

        return CarbonImmutable::createFromTimestamp(
            random_int($startTimestamp, $endTimestamp),
        );
    }
}
