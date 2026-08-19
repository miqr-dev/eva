<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Validation\Rule;
use LogicException;

class UpdateCourseRequest extends AdminRequest
{
    public function rules(): array
    {
        $course = $this->route('course');

        if (! $course instanceof Course) {
            throw new LogicException('Course route binding is missing.');
        }

        $organizationUnitId = $this->integer(
            'organization_unit_id',
            $course->organization_unit_id,
        );

        return [
            'organization_unit_id' => ['sometimes', 'required', 'integer', 'exists:organization_units,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('courses')
                    ->where(
                        fn ($query) => $query->where(
                            'organization_unit_id',
                            $organizationUnitId,
                        ),
                    )
                    ->ignore($course),
            ],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
            'teacher_ids' => ['sometimes', 'array'],
            'teacher_ids.*' => ['integer', 'distinct', 'exists:teachers,id'],
        ];
    }

    protected function permission(): string
    {
        return 'courses.manage';
    }
}
