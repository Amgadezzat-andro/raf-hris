<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('departments')->cascadeOnUpdate()->nullOnDelete();
            $table->string('name');
            $table->string('status')->default('active')->index();
            $table->timestamps();

            $table->index(['branch_id', 'parent_id']);
        });

        Schema::create('job_titles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('status')->default('active')->index();
            $table->timestamps();

            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_titles');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('branches');
    }
};
