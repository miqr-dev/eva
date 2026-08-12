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
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'name']);
        });

        Schema::create('report_template_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_template_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('section_type');
            $table->json('config')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['report_template_id', 'sort_order']);
        });

        Schema::create('report_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_campaign_id')->constrained()->restrictOnDelete();
            $table->foreignId('report_template_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('pending');
            $table->string('file_path')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['evaluation_campaign_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_runs');
        Schema::dropIfExists('report_template_sections');
        Schema::dropIfExists('report_templates');
    }
};
