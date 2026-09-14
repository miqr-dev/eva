<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lets the same teacher be linked to the same course more than once, as
     * long as each link names a different subject. That's what makes it
     * possible to evaluate a teacher who teaches two subjects in one course
     * separately for each subject.
     */
    public function up(): void
    {
        Schema::table('course_teacher', function (Blueprint $table): void {
            $table->foreignId('subject_id')
                ->nullable()
                ->after('teacher_id')
                ->constrained()
                ->nullOnDelete();
        });

        Schema::table('course_teacher', function (Blueprint $table): void {
            $table->dropUnique('course_teacher_course_id_teacher_id_unique');
            $table->unique(
                ['course_id', 'teacher_id', 'subject_id'],
                'course_teacher_course_teacher_subject_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('course_teacher', function (Blueprint $table): void {
            $table->dropUnique('course_teacher_course_teacher_subject_unique');
        });

        Schema::table('course_teacher', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('subject_id');
        });

        Schema::table('course_teacher', function (Blueprint $table): void {
            $table->unique(['course_id', 'teacher_id'], 'course_teacher_course_id_teacher_id_unique');
        });
    }
};
