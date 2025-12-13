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
        Schema::create('kebutuhan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebutuhan_id')->constrained('kebutuhan')->cascadeOnDelete();

            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('total_harga');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhan_item');
    }
};
