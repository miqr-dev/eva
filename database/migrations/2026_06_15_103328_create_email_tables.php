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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('subject');
            $table->longText('body');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });

        Schema::create('email_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_campaign_id')->constrained()->restrictOnDelete();
            $table->foreignId('email_template_id')->constrained()->restrictOnDelete();
            $table->string('type');
            $table->timestamp('scheduled_at')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
            $table->index(['evaluation_campaign_id', 'type']);
        });

        Schema::create('email_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_job_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_type');
            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tan_id')->nullable()->constrained('tans')->nullOnDelete();
            $table->timestamps();

            $table->index(['email_job_id', 'recipient_type']);
            $table->index('recipient_email');
        });

        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('email_recipient_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_email');
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['email_job_id', 'status']);
            $table->index(['email_recipient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('email_recipients');
        Schema::dropIfExists('email_jobs');
        Schema::dropIfExists('email_templates');
    }
};
