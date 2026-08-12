<?php

namespace App\Http\Requests;

use App\Enums\EvaluationCampaignStatus;
use App\Models\EvaluationCampaign;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Validator;

class GenerateTansRequest extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1', 'max:500'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $campaign = $this->route('evaluation_campaign');

                if (! $campaign instanceof EvaluationCampaign) {
                    return;
                }

                if (in_array($campaign->getRawOriginal('status'), [
                    EvaluationCampaignStatus::Closed->value,
                    EvaluationCampaignStatus::Archived->value,
                ], true)) {
                    $validator->errors()->add(
                        'amount',
                        'Für geschlossene oder archivierte Evaluationen können keine neuen TANs erzeugt werden.',
                    );
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Bitte geben Sie die Anzahl der TANs an.',
            'amount.integer' => 'Die Anzahl muss eine ganze Zahl sein.',
            'amount.min' => 'Es muss mindestens eine TAN erzeugt werden.',
            'amount.max' => 'Pro Vorgang können maximal 500 TANs erzeugt werden.',
        ];
    }

    protected function permission(): string
    {
        return 'campaigns.manage';
    }
}
