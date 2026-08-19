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
        Schema::create('questionnaire_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'name']);
        });

        Schema::create('questionnaire_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_template_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->string('default_language', 10)->default('en');
            $table->unsignedInteger('min_answers_to_show_results')->default(5);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['questionnaire_template_id', 'version_number'],
                'qv_template_version_unique',
            );
            $table->index(['status', 'published_at']);
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'name']);
        });

        Schema::create('module_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->string('default_language', 10)->default('en');
            $table->string('target_type')->default('none');
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['module_id', 'version_number']);
            $table->index(['status', 'target_type']);
        });

        Schema::create('questionnaire_version_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_version_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('repeat_mode')->default('once');
            $table->timestamps();

            $table->unique(
                ['questionnaire_version_id', 'module_version_id'],
                'qvm_version_module_unique',
            );
            $table->unique(
                ['questionnaire_version_id', 'sort_order'],
                'qvm_version_sort_unique',
            );
        });

        Schema::create('module_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_version_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['module_version_id', 'sort_order']);
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_section_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->string('question_type');
            $table->integer('scale_min')->nullable();
            $table->integer('scale_max')->nullable();
            $table->string('scale_min_label')->nullable();
            $table->string('scale_max_label')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['module_section_id', 'sort_order']);
            $table->index('question_type');
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('option_text');
            $table->string('value');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['question_id', 'value']);
            $table->unique(['question_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('module_sections');
        Schema::dropIfExists('questionnaire_version_modules');
        Schema::dropIfExists('module_versions');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('questionnaire_versions');
        Schema::dropIfExists('questionnaire_templates');
    }
};
