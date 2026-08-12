<?php

namespace App\Models;

use App\Enums\RepeatMode;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Fillable([
    'questionnaire_version_id',
    'module_version_id',
    'sort_order',
    'repeat_mode',
])]
class QuestionnaireVersionModule extends Pivot
{
    protected $table = 'questionnaire_version_modules';

    public $incrementing = true;

    /** @return BelongsTo<QuestionnaireVersion, $this> */
    public function questionnaireVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireVersion::class);
    }

    /** @return BelongsTo<ModuleVersion, $this> */
    public function moduleVersion(): BelongsTo
    {
        return $this->belongsTo(ModuleVersion::class);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'repeat_mode' => RepeatMode::class,
        ];
    }
}
