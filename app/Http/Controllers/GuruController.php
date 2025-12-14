<?php

// app/Http/Controllers/GuruController.php
namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{

    public function dashboard()
    {
        $jumlahSiswa  = Siswa::count();
        $presensiHari = Presensi::whereDate('tgl_presensi', today())->count(); // atau tgl_presensi sesuai tabel

        $userName = Auth::user()->name ?? 'User';

        return view('guru.dashboard', compact('jumlahSiswa', 'presensiHari', 'userName'));
    }


    // Siswa
    public function indexSiswa()
    {
        $siswa = Siswa::orderBy('nama_lengkap')->get();
        return view('guru.siswa.index', compact('siswa'));
    }

    public function createSiswa()
    {
        return view('guru.siswa.create');
    }

    public function storeSiswa(Request $request)
    {
        $data = $request->validate([
            'nis'           => 'required|string|max:50|unique:siswa,nis',
            'nama_lengkap'  => 'required|string|max:255',
            'kelas'         => 'required|string|max:50',
            'status'        => 'required|in:Aktif,Tidak Aktif',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        Siswa::create($data);
        return redirect()->route('guru.siswa.index')->with('success', 'Siswa tersimpan.');
    }

    // Presensi
    public function indexPresensi()
    {
        $presensis = Presensi::with('pegawai')->orderByDesc('tgl_presensi')->get();
        return view('guru.presensi.index', compact('presensis'));
    }

    public function createPresensi()
    {
        $pegawai = Pegawai::orderBy('nama')->get();
        return view('guru.presensi.create', compact('pegawai'));
    }

    public function storePresensi(Request $request)
    {
        $data = $request->validate([
            'id_pegawai'      => 'required|exists:pegawai,id',
            'tgl_presensi'    => 'required|date',
            'waktu_masuk'     => 'required',
            'waktu_keluar'    => 'required',
            'status_presensi' => 'required|string',
        ]);

        Presensi::create($data);
        return redirect()->route('guru.presensi.index')->with('success', 'Presensi tersimpan.');
    }
}