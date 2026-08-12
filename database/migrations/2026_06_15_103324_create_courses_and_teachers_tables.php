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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_unit_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('code');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['organization_unit_id', 'code']);
            $table->index(['organization_unit_id', 'is_active']);
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('organization_unit_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['organization_unit_id', 'is_active']);
            $table->index('email');
        });

        Schema::create('course_teacher', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_teacher');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('courses');
    }
};
