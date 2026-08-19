<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'report_template_id',
    'title',
    'section_type',
    'config',
    'sort_order',
])]
class ReportTemplateSection extends Model
{
    /** @return BelongsTo<ReportTemplate, $this> */
    public function reportTemplate(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class);
    }

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'sort_order' => 'integer',
        ];
    }
}
