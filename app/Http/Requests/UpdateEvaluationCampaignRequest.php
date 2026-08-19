<?php

namespace App\Http\Requests;

use App\Enums\EvaluationCampaignStatus;
use App\Enums\PublicationStatus;
use Illuminate\Validation\Rule;

class UpdateEvaluationCampaignRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'organization_unit_id' => ['sometimes', 'required', 'integer', 'exists:organization_units,id'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'questionnaire_version_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('questionnaire_versions', 'id')->where(
                    'status',
                    PublicationStatus::Published->value,
                ),
            ],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'status' => ['sometimes', Rule::enum(EvaluationCampaignStatus::class)],
            'min_answers_to_show_results' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    protected function permission(): string
    {
        return 'campaigns.manage';
    }
}
