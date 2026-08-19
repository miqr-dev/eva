<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'module_section_id',
    'question_text',
    'question_type',
    'scale_min',
    'scale_max',
    'scale_min_label',
    'scale_max_label',
    'is_required',
    'sort_order',
])]
class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    /** @return BelongsTo<ModuleSection, $this> */
    public function moduleSection(): BelongsTo
    {
        return $this->belongsTo(ModuleSection::class);
    }

    /** @return HasMany<QuestionOption, $this> */
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)
            ->orderBy('sort_order');
    }

    /** @return HasMany<ResponseAnswer, $this> */
    public function responseAnswers(): HasMany
    {
        return $this->hasMany(ResponseAnswer::class);
    }

    /** @return HasMany<BenchmarkSnapshot, $this> */
    public function benchmarkSnapshots(): HasMany
    {
        return $this->hasMany(BenchmarkSnapshot::class);
    }

    protected function casts(): array
    {
        return [
            'question_type' => QuestionType::class,
            'scale_min' => 'integer',
            'scale_max' => 'integer',
            'is_required' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
