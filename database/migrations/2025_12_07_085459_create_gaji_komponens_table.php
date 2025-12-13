<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('gaji_komponen', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('gaji_id');
        $table->unsignedBigInteger('komponen_gaji_id');
        $table->decimal('nominal', 12, 2)->default(0);
        $table->timestamps();

        $table->foreign('gaji_id')->references('id')->on('gaji')->onDelete('cascade');
        $table->foreign('komponen_gaji_id')->references('id')->on('komponen_gaji')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_komponen');
    }
};
