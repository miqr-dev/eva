<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionnaireTemplateRequest;
use App\Http\Requests\UpdateQuestionnaireTemplateRequest;
use App\Http\Resources\QuestionnaireTemplateResource;
use App\Models\QuestionnaireTemplate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class QuestionnaireTemplateController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('questionnaires.manage');

        $questionnaireTemplates = QuestionnaireTemplate::query()
            ->with('creator')
            ->withCount('versions')
            ->latest()
            ->paginate(25);

        return QuestionnaireTemplateResource::collection($questionnaireTemplates);
    }

    public function store(
        StoreQuestionnaireTemplateRequest $request,
    ): QuestionnaireTemplateResource {
        $questionnaireTemplate = QuestionnaireTemplate::query()->create([
            ...$request->validated(),
            'created_by_id' => $request->user()->id,
        ]);

        return new QuestionnaireTemplateResource(
            $questionnaireTemplate->load('creator'),
        );
    }

    public function show(
        QuestionnaireTemplate $questionnaireTemplate,
    ): QuestionnaireTemplateResource {
        $this->authorizePermission('questionnaires.manage');

        return new QuestionnaireTemplateResource(
            $questionnaireTemplate->load(['creator', 'versions']),
        );
    }

    public function update(
        UpdateQuestionnaireTemplateRequest $request,
        QuestionnaireTemplate $questionnaireTemplate,
    ): QuestionnaireTemplateResource {
        $questionnaireTemplate->update($request->validated());

        return new QuestionnaireTemplateResource(
            $questionnaireTemplate->load('creator'),
        );
    }

    public function destroy(QuestionnaireTemplate $questionnaireTemplate): Response
    {
        $this->authorizePermission('questionnaires.manage');

        $questionnaireTemplate->delete();

        return response()->noContent();
    }
}
