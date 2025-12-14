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
    Schema::create('pengeluaran', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');

        // definisikan kolom FK dulu
        $table->unsignedBigInteger('kategori_pengeluaran_id');

        $table->string('sumber_type');
        $table->unsignedBigInteger('sumber_id')->nullable();
        $table->string('nama_pengeluaran');
        $table->integer('jumlah');
        $table->decimal('harga_satuan', 15, 2)->nullable();
        $table->decimal('total', 15, 2)->nullable();
        $table->text('catatan')->nullable();
        $table->timestamps();

        // baru kemudian foreign key‑nya
        $table->foreign('kategori_pengeluaran_id')
              ->references('id')
              ->on('kategori_pengeluarans')
              ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
