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
        Schema::create('organization_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organization_units')
                ->nullOnDelete();
            $table->string('name');
            $table->string('type');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['parent_id', 'sort_order']);
            $table->index(['type', 'is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_unit_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('password');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('user_permission', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permission');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_unit_id');
            $table->dropColumn('is_active');
        });

        Schema::dropIfExists('organization_units');
    }
};
