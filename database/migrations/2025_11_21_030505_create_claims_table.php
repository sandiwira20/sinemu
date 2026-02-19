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
    Schema::create('claims', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users'); // mahasiswa
        $table->foreignId('item_id')->constrained('items');
        $table->foreignId('lost_report_id')->nullable()->constrained('lost_reports');
        $table->text('description')->nullable(); // ciri-ciri khusus tambahan
        $table->string('contact');
        $table->dateTime('claimed_at');
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->foreignId('verified_by')->nullable()->constrained('users'); // admin
        $table->dateTime('verified_at')->nullable();
        $table->string('evidence_file')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};