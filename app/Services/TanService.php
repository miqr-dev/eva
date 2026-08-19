<?php

namespace App\Services;

use App\Enums\EvaluationCampaignStatus;
use App\Models\Tan;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TanService
{
    public function hash(string $plainTan): string
    {
        return hash('sha256', $this->normalize($plainTan));
    }

    public function validateForStart(string $plainTan): Tan
    {
        $tan = Tan::query()
            ->with('evaluationCampaign')
            ->where('tan_code_hash', $this->hash($plainTan))
            ->where('is_active', true)
            ->first();

        if (! $tan instanceof Tan) {
            throw ValidationException::withMessages([
                'tan_code' => 'Diese TAN ist ungültig.',
            ]);
        }

        $this->ensureCanBeUsed($tan);

        if ($tan->started_at === null) {
            $tan->update(['started_at' => now()]);
        }

        return $tan->refresh()->load('evaluationCampaign');
    }

    public function ensureCanBeUsed(Tan $tan): void
    {
        $tan->loadMissing('evaluationCampaign');

        if (! $tan->is_active) {
            throw ValidationException::withMessages([
                'tan_code' => 'Diese TAN ist nicht aktiv.',
            ]);
        }

        if ($tan->used_at !== null) {
            throw ValidationException::withMessages([
                'tan_code' => 'Diese TAN wurde bereits verwendet.',
            ]);
        }

        if ($tan->expires_at !== null && now()->gt($tan->expires_at)) {
            throw ValidationException::withMessages([
                'tan_code' => 'Diese TAN ist abgelaufen.',
            ]);
        }

        $campaign = $tan->evaluationCampaign;

        if ($campaign->getRawOriginal('status') !== EvaluationCampaignStatus::Active->value) {
            throw ValidationException::withMessages([
                'tan_code' => 'Diese Evaluation ist derzeit nicht aktiv.',
            ]);
        }

        if ($campaign->starts_at !== null && now()->lt($campaign->starts_at)) {
            throw ValidationException::withMessages([
                'tan_code' => 'Diese Evaluation hat noch nicht begonnen.',
            ]);
        }

        if ($campaign->ends_at !== null && now()->gt($campaign->ends_at)) {
            throw ValidationException::withMessages([
                'tan_code' => 'Diese Evaluation ist bereits geschlossen.',
            ]);
        }
    }

    private function normalize(string $plainTan): string
    {
        return Str::of($plainTan)
            ->trim()
            ->upper()
            ->replaceMatches('/\s+/', '')
            ->toString();
    }
}
