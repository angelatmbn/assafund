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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            //$table->string('id_presensi')->unique();
            $table->foreignId('id_pegawai')->constrained('pegawai')->cascadeOnDelete();
            $table->date('tgl_presensi'); 
            $table->time('waktu_masuk');
            $table->time('waktu_keluar');
            $table->enum('status_presensi', ['hadir', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
