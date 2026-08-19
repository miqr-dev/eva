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
        Schema::create('benchmark_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('scope_type');
            $table->foreignId('organization_unit_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(
                ['scope_type', 'organization_unit_id', 'is_active'],
                'benchmark_group_scope_unit_index',
            );
        });

        Schema::create('benchmark_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benchmark_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->restrictOnDelete();
            $table->string('target_type')->nullable();
            $table->decimal('average_value', 12, 4);
            $table->unsignedInteger('response_count');
            $table->timestamp('calculated_at');
            $table->timestamps();

            $table->index(
                ['benchmark_group_id', 'question_id', 'target_type', 'calculated_at'],
                'benchmark_snapshot_lookup_index',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benchmark_snapshots');
        Schema::dropIfExists('benchmark_groups');
    }
};
