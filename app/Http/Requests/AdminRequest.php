<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

abstract class AdminRequest extends FormRequest
{
    final public function authorize(): bool
    {
        return $this->user()?->hasPermission($this->permission()) ?? false;
    }

    abstract protected function permission(): string;

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    abstract public function rules(): array;
}
