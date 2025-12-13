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
    Schema::create('kebutuhan', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal')->nullable();
        $table->bigInteger('total_nominal')->default(0);

        $table->string('status')->default('pending'); // pending, approved, rejected

        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->timestamp('approved_at')->nullable();

        $table->timestamps();

        $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhan');
    }
};
