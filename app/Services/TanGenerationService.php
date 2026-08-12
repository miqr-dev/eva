<?php

namespace App\Services;

use App\Models\EvaluationCampaign;
use App\Models\Tan;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TanGenerationService
{
    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function __construct(private TanService $tanService) {}

    /**
     * @return array<int, string>
     */
    public function generate(EvaluationCampaign $campaign, int $amount): array
    {
        return DB::transaction(function () use ($campaign, $amount): array {
            $codes = [];

            for ($index = 0; $index < $amount; $index++) {
                $code = $this->generateUniqueCode();

                Tan::query()->create([
                    'evaluation_campaign_id' => $campaign->id,
                    'tan_code_hash' => $this->tanService->hash($code),
                    'expires_at' => $campaign->ends_at,
                    'is_active' => true,
                ]);

                $codes[] = $code;
            }

            return $codes;
        });
    }

    private function generateUniqueCode(): string
    {
        for ($attempt = 0; $attempt < 100; $attempt++) {
            $code = $this->generateCode();

            if (! Tan::query()
                ->where('tan_code_hash', $this->tanService->hash($code))
                ->exists()) {
                return $code;
            }
        }

        throw new RuntimeException('Unable to generate a unique TAN code.');
    }

    private function generateCode(): string
    {
        return $this->generateGroup().'-'.$this->generateGroup();
    }

    private function generateGroup(): string
    {
        $group = '';
        $maximumIndex = strlen(self::ALPHABET) - 1;

        for ($index = 0; $index < 4; $index++) {
            $group .= self::ALPHABET[random_int(0, $maximumIndex)];
        }

        return $group;
    }
}
