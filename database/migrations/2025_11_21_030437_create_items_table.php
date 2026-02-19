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
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained('categories');
        $table->foreignId('created_by')->constrained('users'); // admin
        $table->string('name');
        $table->text('description')->nullable();
        $table->enum('status', ['stored', 'claimed', 'returned', 'discarded'])->default('stored');
        $table->string('found_location');
        $table->dateTime('found_at');
        $table->string('stored_location')->nullable();
        $table->string('contact')->nullable();
        $table->string('main_image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};