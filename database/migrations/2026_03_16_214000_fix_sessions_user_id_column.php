<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sessions')) {
            return;
        }

        if (Schema::hasColumn('sessions', 'employee_id') && ! Schema::hasColumn('sessions', 'user_id')) {
            Schema::table('sessions', function (Blueprint $table): void {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            });

            DB::table('sessions')->update([
                'user_id' => DB::raw('employee_id'),
            ]);

            Schema::table('sessions', function (Blueprint $table): void {
                $table->dropColumn('employee_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('sessions')) {
            return;
        }

        if (Schema::hasColumn('sessions', 'user_id') && ! Schema::hasColumn('sessions', 'employee_id')) {
            Schema::table('sessions', function (Blueprint $table): void {
                $table->unsignedBigInteger('employee_id')->nullable()->index();
            });

            DB::table('sessions')->update([
                'employee_id' => DB::raw('user_id'),
            ]);

            Schema::table('sessions', function (Blueprint $table): void {
                $table->dropColumn('user_id');
            });
        }
    }
};
