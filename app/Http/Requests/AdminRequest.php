<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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

    /**
     * Fail validation when the given list of {$entityKey, subject_id}
     * assignment rows contains two rows for the same entity and subject
     * (including two rows that both leave the subject unset) — a plain
     * belongsToMany sync() can store any number of such rows, but they'd be
     * indistinguishable duplicates.
     */
    protected function ensureAssignmentsAreDistinct(
        Validator $validator,
        string $field,
        string $entityKey,
    ): void {
        $assignments = $this->input($field, []);

        if (! is_array($assignments)) {
            return;
        }

        $seen = [];

        foreach ($assignments as $index => $assignment) {
            if (! is_array($assignment)) {
                continue;
            }

            $key = ($assignment[$entityKey] ?? 'null').':'.($assignment['subject_id'] ?? 'null');

            if (isset($seen[$key])) {
                $validator->errors()->add(
                    "{$field}.{$index}.{$entityKey}",
                    'Diese Zuordnung ist bereits vorhanden.',
                );

                continue;
            }

            $seen[$key] = true;
        }
    }
}
