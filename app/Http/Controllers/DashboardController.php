<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\EvaluationCampaign;
use App\Models\OrganizationUnit;
use App\Models\QuestionnaireTemplate;
use App\Models\Teacher;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'organizationUnits' => OrganizationUnit::query()->count(),
                'users' => User::query()->count(),
                'courses' => Course::query()->count(),
                'teachers' => Teacher::query()->count(),
                'questionnaires' => QuestionnaireTemplate::query()->count(),
                'campaigns' => EvaluationCampaign::query()->count(),
            ],
            'recentCampaigns' => EvaluationCampaign::query()
                ->select(['id', 'title', 'status', 'starts_at', 'ends_at'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
