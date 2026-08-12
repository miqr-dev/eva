<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'benchmark_group_id',
    'question_id',
    'target_type',
    'average_value',
    'response_count',
    'calculated_at',
])]
class BenchmarkSnapshot extends Model
{
    /** @return BelongsTo<BenchmarkGroup, $this> */
    public function benchmarkGroup(): BelongsTo
    {
        return $this->belongsTo(BenchmarkGroup::class);
    }

    /** @return BelongsTo<Question, $this> */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    protected function casts(): array
    {
        return [
            'average_value' => 'decimal:4',
            'response_count' => 'integer',
            'calculated_at' => 'datetime',
        ];
    }
}
