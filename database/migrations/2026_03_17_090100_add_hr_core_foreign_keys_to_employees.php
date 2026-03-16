<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table): void {
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('job_title_id')->references('id')->on('job_titles')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table): void {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['job_title_id']);
        });
    }
};
