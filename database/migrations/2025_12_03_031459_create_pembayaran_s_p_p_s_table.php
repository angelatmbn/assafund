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
        Schema::create('pembayaran_s_p_p_', function (Blueprint $table) {
            $table->id();
            $table->string("nis"); // foreign key ke tabel siswa
            $table->string("bulan");
            $table->string("tahun");
            $table->date("tanggal_bayar");
            $table->integer("biaya_pokok");
            $table->timestamps();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_s_p_p_');
    }
};
