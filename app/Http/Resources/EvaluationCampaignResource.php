<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\EvaluationCampaign
 */
class EvaluationCampaignResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_unit_id' => $this->organization_unit_id,
            'course_id' => $this->course_id,
            'questionnaire_version_id' => $this->questionnaire_version_id,
            'title' => $this->title,
            'description' => $this->description,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'status' => $this->status,
            'min_answers_to_show_results' => $this->min_answers_to_show_results,
            'created_by_id' => $this->created_by_id,
            'organization_unit' => new OrganizationUnitResource(
                $this->whenLoaded('organizationUnit'),
            ),
            'course' => new CourseResource($this->whenLoaded('course')),
            'questionnaire_version' => $this->whenLoaded(
                'questionnaireVersion',
                fn () => $this->questionnaireVersion->only([
                    'id',
                    'version_number',
                    'title',
                    'status',
                ]),
            ),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'targets' => EvaluationCampaignTargetResource::collection(
                $this->whenLoaded('targets'),
            ),
            'targets_count' => $this->whenCounted('targets'),
            'responses_count' => $this->whenCounted('responses'),
            'tans_count' => $this->whenCounted('tans'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
