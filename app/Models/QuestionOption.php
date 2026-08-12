<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['question_id', 'option_text', 'value', 'sort_order'])]
class QuestionOption extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionOptionFactory> */
    use HasFactory;

    /** @return BelongsTo<Question, $this> */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /** @return HasMany<ResponseAnswer, $this> */
    public function responseAnswers(): HasMany
    {
        return $this->hasMany(ResponseAnswer::class);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
