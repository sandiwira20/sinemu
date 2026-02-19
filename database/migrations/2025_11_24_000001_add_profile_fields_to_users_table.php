<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'display_name')) {
                $table->string('display_name', 100)->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name', 150)->nullable()->after('display_name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'display_name')) {
                $columns[] = 'display_name';
            }
            if (Schema::hasColumn('users', 'full_name')) {
                $columns[] = 'full_name';
            }
            if (Schema::hasColumn('users', 'phone')) {
                $columns[] = 'phone';
            }
            if (Schema::hasColumn('users', 'photo')) {
                $columns[] = 'photo';
            }
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
