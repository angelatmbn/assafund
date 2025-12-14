<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebersihanController extends Controller
{
    public function dashboard()
    {
        $presensiHari = Presensi::whereDate('tgl_presensi', today())->count(); // atau tgl_presensi
        $userName     = Auth::user()->name ?? 'User';

        return view('kebersihan.dashboard', compact('presensiHari', 'userName'));
    }

    public function indexPresensi()
    {
        $presensis = Presensi::with('pegawai')
            ->orderByDesc('tgl_presensi')   // sesuaikan nama kolom tanggal
            ->get();

        return view('kebersihan.presensi.index', compact('presensis'));
    }

    public function createPresensi()
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        return view('kebersihan.presensi.create', compact('pegawai'));
    }

    public function storePresensi(Request $request)
    {
        $data = $request->validate([
            'id_pegawai'      => 'required|exists:pegawais,id',
            'tgl_presensi'   => 'required|date',
            'waktu_masuk'     => 'required',
            'waktu_keluar'    => 'required',
            'status_presensi' => 'required|string',
        ]);

        Presensi::create($data);

        return redirect()->route('kebersihan.presensi.index')->with('success', 'Presensi tersimpan.');
    }
}
