<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('module_versions', function (Blueprint $table): void {
            $table->foreignId('target_role_id')
                ->nullable()
                ->after('target_type')
                ->constrained('teacher_roles')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('module_versions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('target_role_id');
        });
    }
};
