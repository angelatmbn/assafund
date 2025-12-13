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
        Schema::create('gaji', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur', 50)->unique();
            $table->foreignId('id_pegawai')->constrained('pegawai')->cascadeOnDelete();
            $table->year('tahun_gaji');
            $table->enum('bulan_gaji', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ]); 
            $table->date('tgl_gaji');
            $table->integer('jumlah_hadir');
            $table->decimal('total_gaji', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji');
    }
};
