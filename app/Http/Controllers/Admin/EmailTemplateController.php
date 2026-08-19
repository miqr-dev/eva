<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailTemplateRequest;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Http\Resources\EmailTemplateResource;
use App\Models\EmailTemplate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class EmailTemplateController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorizePermission('emails.manage');

        $emailTemplates = EmailTemplate::query()
            ->latest()
            ->paginate(25);

        return EmailTemplateResource::collection($emailTemplates);
    }

    public function store(StoreEmailTemplateRequest $request): EmailTemplateResource
    {
        $emailTemplate = EmailTemplate::query()->create($request->validated());

        return new EmailTemplateResource($emailTemplate);
    }

    public function show(EmailTemplate $emailTemplate): EmailTemplateResource
    {
        $this->authorizePermission('emails.manage');

        return new EmailTemplateResource($emailTemplate);
    }

    public function update(
        UpdateEmailTemplateRequest $request,
        EmailTemplate $emailTemplate,
    ): EmailTemplateResource {
        $emailTemplate->update($request->validated());

        return new EmailTemplateResource($emailTemplate);
    }

    public function destroy(EmailTemplate $emailTemplate): Response
    {
        $this->authorizePermission('emails.manage');

        $emailTemplate->delete();

        return response()->noContent();
    }
}
