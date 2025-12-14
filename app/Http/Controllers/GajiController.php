<?php

namespace App\Http\Controllers;

use App\Models\Gaji;

class GajiController extends Controller
{
    public function slipGaji(Gaji $gaji)
    {
        // load relasi
        $gaji->load('pegawai.jabatan', 'komponenGaji');

        // gaji pokok
        $gajiPokok = (float) ($gaji->gaji_pokok ?? 0);

        // hitung tunjangan & potongan dari komponen_gaji
        $tunjangan = $gaji->komponenGaji
            ->where('jenis', 'tunjangan')
            ->sum('nominal');

        // total gaji
        $totalGaji = $gajiPokok + $tunjangan;

        // kirim ke view
        return view('gaji.slip-gaji', [
            'gaji'       => $gaji,
            'pegawai'    => $gaji->pegawai,
            'gajiPokok'  => $gajiPokok,
            'tunjangan'  => $tunjangan,
            'totalGaji'  => $totalGaji,
        ]);
    }
}
