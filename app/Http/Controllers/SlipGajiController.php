<?php

namespace App\Http\Controllers;

use App\Models\Gaji;

class GajiController extends Controller
{
    public function slipGaji(Gaji $gaji)
    {
        // load relasi
        $gaji->load('pegawai.jabatan', 'komponenGaji');

        // ambil nilai dasar
        $gajiPokok = (float) ($gaji->gaji_pokok ?? 0);
        $tunjangan = (float) ($gaji->tunjangan_total ?? 0);

        // hitung ulang total gaji (WAJIB)
        $totalGaji = $gajiPokok + $tunjangan ;

        // kirim ke blade
        return view('gaji.slip-gaji', [
            'gaji'       => $gaji,
            'pegawai'    => $gaji->pegawai,
            'gajiPokok'  => $gajiPokok,
            'tunjangan'  => $tunjangan,
            'totalGaji'  => $totalGaji,
        ]);
    }
}
