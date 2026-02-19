<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lost_reports', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('user_id')->constrained('categories')->nullOnDelete();
            $table->string('contact')->nullable()->after('lost_location');
            $table->string('evidence_file')->nullable()->after('contact');
        });
    }

    public function down(): void
    {
        Schema::table('lost_reports', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'contact', 'evidence_file']);
        });
    }
};
