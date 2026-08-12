<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BenchmarkGroupResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\EmailTemplateResource;
use App\Http\Resources\EvaluationCampaignResource;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\OrganizationUnitResource;
use App\Http\Resources\QuestionnaireTemplateResource;
use App\Http\Resources\ReportTemplateResource;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\UserResource;
use App\Models\BenchmarkGroup;
use App\Models\Course;
use App\Models\EmailTemplate;
use App\Models\EvaluationCampaign;
use App\Models\Module;
use App\Models\OrganizationUnit;
use App\Models\Permission;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\ReportTemplate;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class AdminPageController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const PERMISSIONS = [
        'organisationseinheiten' => 'organization_units.manage',
        'benutzer' => 'users.manage',
        'kurse' => 'courses.manage',
        'lehrende' => 'courses.manage',
        'frageboegen' => 'questionnaires.manage',
        'module' => 'questionnaires.manage',
        'evaluationen' => 'campaigns.manage',
        'berichtsvorlagen' => 'reports.manage',
        'benchmarks' => 'benchmarks.manage',
        'email-vorlagen' => 'emails.manage',
    ];

    public function __invoke(Request $request, string $resource): Response
    {
        $user = $request->user();
        $permission = self::PERMISSIONS[$resource] ?? null;

        abort_unless(
            $user instanceof User
                && $permission !== null
                && $user->hasPermission($permission),
            403,
        );

        return Inertia::render('admin/ResourceIndex', [
            'resourceKey' => $resource,
            'records' => $this->records($request, $resource),
            'options' => $this->options(),
        ]);
    }

    /**
     * @return array<int, mixed>
     */
    private function records(Request $request, string $resource): array
    {
        return match ($resource) {
            'organisationseinheiten' => $this->resolveCollection(
                $request,
                OrganizationUnitResource::collection(
                    OrganizationUnit::query()
                        ->with('parent')
                        ->withCount(['children', 'users', 'courses'])
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get(),
                ),
            ),
            'benutzer' => $this->resolveCollection(
                $request,
                UserResource::collection(
                    User::query()
                        ->with(['organizationUnit', 'roles', 'permissions'])
                        ->latest()
                        ->get(),
                ),
            ),
            'kurse' => $this->resolveCollection(
                $request,
                CourseResource::collection(
                    Course::query()
                        ->with(['organizationUnit', 'teachers'])
                        ->latest()
                        ->get(),
                ),
            ),
            'lehrende' => $this->resolveCollection(
                $request,
                TeacherResource::collection(
                    Teacher::query()
                        ->with(['organizationUnit', 'user', 'courses'])
                        ->latest()
                        ->get(),
                ),
            ),
            'frageboegen' => $this->resolveCollection(
                $request,
                QuestionnaireTemplateResource::collection(
                    QuestionnaireTemplate::query()
                        ->with('creator')
                        ->withCount('versions')
                        ->latest()
                        ->get(),
                ),
            ),
            'module' => $this->resolveCollection(
                $request,
                ModuleResource::collection(
                    Module::query()
                        ->with('creator')
                        ->withCount('versions')
                        ->latest()
                        ->get(),
                ),
            ),
            'evaluationen' => $this->resolveCollection(
                $request,
                EvaluationCampaignResource::collection(
                    EvaluationCampaign::query()
                        ->with(['organizationUnit', 'course', 'questionnaireVersion'])
                        ->withCount(['targets', 'responses', 'tans'])
                        ->latest()
                        ->get(),
                ),
            ),
            'berichtsvorlagen' => $this->resolveCollection(
                $request,
                ReportTemplateResource::collection(
                    ReportTemplate::query()
                        ->with('creator')
                        ->withCount('sections')
                        ->latest()
                        ->get(),
                ),
            ),
            'benchmarks' => $this->resolveCollection(
                $request,
                BenchmarkGroupResource::collection(
                    BenchmarkGroup::query()
                        ->with('organizationUnit')
                        ->withCount('snapshots')
                        ->latest()
                        ->get(),
                ),
            ),
            'email-vorlagen' => $this->resolveCollection(
                $request,
                EmailTemplateResource::collection(
                    EmailTemplate::query()->latest()->get(),
                ),
            ),
            default => abort(404),
        };
    }

    /**
     * @return array<int, mixed>
     */
    private function resolveCollection(
        Request $request,
        AnonymousResourceCollection $resources,
    ): array {
        $responseData = $resources->response($request)->getData(true);
        $records = $responseData['data'] ?? [];

        return is_array($records) ? array_values($records) : [];
    }

    /**
     * @return array<string, array<int, array{value?: int|string, label: string, options?: array<int, array{value: int|string, label: string}>}>>
     */
    private function options(): array
    {
        return [
            'organizationUnits' => OrganizationUnit::query()
                ->whereNull('parent_id')
                ->with(['children' => fn ($query) => $query
                    ->orderBy('sort_order')
                    ->orderBy('name')])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name', 'sort_order'])
                ->map(fn (OrganizationUnit $state): array => [
                    'label' => $state->name,
                    'options' => $state->children
                        ->map(fn (OrganizationUnit $location): array => [
                            'value' => $location->id,
                            'label' => $location->name,
                        ])
                        ->values()
                        ->all(),
                ])
                ->filter(fn (array $group): bool => count($group['options']) > 0)
                ->values()
                ->all(),
            'organizationUnitParents' => OrganizationUnit::query()
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (OrganizationUnit $unit): array => [
                    'value' => $unit->id,
                    'label' => $unit->name,
                ])
                ->all(),
            'users' => User::query()
                ->orderBy('name')
                ->get(['id', 'name', 'email'])
                ->map(fn (User $user): array => [
                    'value' => $user->id,
                    'label' => "{$user->name} ({$user->email})",
                ])
                ->all(),
            'roles' => Role::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Role $role): array => [
                    'value' => $role->id,
                    'label' => $this->roleLabel($role->name),
                ])
                ->all(),
            'permissions' => Permission::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Permission $permission): array => [
                    'value' => $permission->id,
                    'label' => $this->permissionLabel($permission->name),
                ])
                ->all(),
            'courses' => Course::query()
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
                ->map(fn (Course $course): array => [
                    'value' => $course->id,
                    'label' => "{$course->code} · {$course->name}",
                ])
                ->all(),
            'teachers' => Teacher::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Teacher $teacher): array => [
                    'value' => $teacher->id,
                    'label' => $teacher->name,
                ])
                ->all(),
            'questionnaireVersions' => QuestionnaireVersion::query()
                ->where('status', 'published')
                ->with('questionnaireTemplate:id,name')
                ->orderBy('title')
                ->get(['id', 'questionnaire_template_id', 'version_number', 'title'])
                ->map(fn (QuestionnaireVersion $version): array => [
                    'value' => $version->id,
                    'label' => "{$version->title} · Version {$version->version_number}",
                ])
                ->all(),
        ];
    }

    private function roleLabel(string $role): string
    {
        return match ($role) {
            'super-admin' => 'Super-Administration',
            'administrator' => 'Administration',
            'questionnaire-editor' => 'Fragebogen-Redaktion',
            'report-viewer' => 'Berichtszugriff',
            default => $role,
        };
    }

    private function permissionLabel(string $permission): string
    {
        return match ($permission) {
            'organization_units.manage' => 'Standorte verwalten',
            'users.manage' => 'Benutzer verwalten',
            'courses.manage' => 'Kurse und Lehrende verwalten',
            'questionnaires.manage' => 'Fragebögen und Module verwalten',
            'campaigns.manage' => 'Evaluationen verwalten',
            'responses.view' => 'Antworten einsehen',
            'reports.manage' => 'Berichte verwalten',
            'benchmarks.manage' => 'Benchmarks verwalten',
            'emails.manage' => 'E-Mail-Kommunikation verwalten',
            default => $permission,
        };
    }
}
