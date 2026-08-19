<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_unit_id')->constrained()->restrictOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('questionnaire_version_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('min_answers_to_show_results')->default(5);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_unit_id', 'status']);
            $table->index(['starts_at', 'ends_at']);
        });

        Schema::create('evaluation_campaign_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_campaign_id')->constrained()->cascadeOnDelete();
            $table->morphs('target');
            $table->string('label');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(
                ['evaluation_campaign_id', 'target_type', 'target_id'],
                'campaign_target_unique',
            );
            $table->unique(
                ['evaluation_campaign_id', 'sort_order'],
                'campaign_target_sort_unique',
            );
        });

        Schema::create('tans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_campaign_id')->constrained()->cascadeOnDelete();
            $table->char('tan_code_hash', 64)->unique();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['evaluation_campaign_id', 'is_active', 'expires_at']);
        });

        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_campaign_id')->constrained()->restrictOnDelete();
            $table->foreignId('tan_id')
                ->nullable()
                ->unique()
                ->constrained('tans')
                ->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->string('language', 10)->default('en');
            $table->text('user_agent')->nullable();
            $table->char('ip_hash', 64)->nullable();
            $table->timestamps();

            $table->index(['evaluation_campaign_id', 'submitted_at']);
        });

        Schema::create('response_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('responses')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->restrictOnDelete();
            $table->foreignId('evaluation_campaign_target_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('question_option_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();
            $table->decimal('numeric_value', 12, 4)->nullable();
            $table->text('text_value')->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->timestamps();

            $table->index(
                ['response_id', 'question_id', 'evaluation_campaign_target_id'],
                'response_question_target_index',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_answers');
        Schema::dropIfExists('responses');
        Schema::dropIfExists('tans');
        Schema::dropIfExists('evaluation_campaign_targets');
        Schema::dropIfExists('evaluation_campaigns');
    }
};
