<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Without this, a teacher can only appear once per campaign (the unique
     * index was on campaign+type+teacher alone). Adding subject_id to the
     * index lets the same teacher appear as two separate targets in one
     * campaign, one per subject they're being evaluated on.
     */
    public function up(): void
    {
        Schema::table('evaluation_campaign_targets', function (Blueprint $table): void {
            $table->foreignId('subject_id')
                ->nullable()
                ->after('target_id')
                ->constrained()
                ->nullOnDelete();
        });

        Schema::table('evaluation_campaign_targets', function (Blueprint $table): void {
            $table->dropUnique('campaign_target_unique');
            $table->unique(
                ['evaluation_campaign_id', 'target_type', 'target_id', 'subject_id'],
                'campaign_target_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_campaign_targets', function (Blueprint $table): void {
            $table->dropUnique('campaign_target_unique');
        });

        Schema::table('evaluation_campaign_targets', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('subject_id');
        });

        Schema::table('evaluation_campaign_targets', function (Blueprint $table): void {
            $table->unique(
                ['evaluation_campaign_id', 'target_type', 'target_id'],
                'campaign_target_unique',
            );
        });
    }
};
