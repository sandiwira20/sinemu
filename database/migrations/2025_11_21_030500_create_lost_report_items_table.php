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
    Schema::create('lost_report_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lost_report_id')->constrained('lost_reports')->onDelete('cascade');
        $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
        $table->dateTime('matched_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_report_items');
    }
};