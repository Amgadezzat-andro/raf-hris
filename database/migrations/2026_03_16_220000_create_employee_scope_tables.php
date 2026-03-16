<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_branches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();

            $table->unique(['employee_id', 'branch_id']);
            $table->index('branch_id');
        });

        Schema::create('employee_departments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('department_id');
            $table->timestamps();

            $table->unique(['employee_id', 'department_id']);
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_departments');
        Schema::dropIfExists('employee_branches');
    }
};
