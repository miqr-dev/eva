<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitEvaluationResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'language' => ['nullable', 'string', 'max:10'],
            'answers' => ['required', 'array'],
        ];
    }
}
