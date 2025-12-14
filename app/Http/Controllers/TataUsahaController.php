<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\Siswa;
use App\Models\Gaji;
use App\Models\PembayaranSPP;
use App\Models\Pendaftaran;
use App\Models\Jabatan;
use App\Models\Coa;        // sesuaikan nama model
use App\Models\KomponenBiayaDaftar;
use Carbon\Carbon;



class TataUsahaController extends Controller
{
    // DASHBOARD
public function dashboard()
{
    // existing
    $userName         = Auth::user()->name ?? 'User';
    $totalPegawai     = Pegawai::count();
    $totalSiswa       = Siswa::count();
    $totalNominalGaji = Gaji::sum('total_gaji');
    $totalNominalSPP  = PembayaranSPP::sum('biaya_pokok');
    $jumlahPresensi   = Presensi::count();

    // tren gaji & SPP 6 bulan terakhir
$gajiPerBulan = Gaji::selectRaw(
        'DATE_FORMAT(tgl_gaji, "%Y-%m") as bulan, SUM(total_gaji) as total'
    )
    ->groupByRaw('DATE_FORMAT(tgl_gaji, "%Y-%m")')
    ->orderBy('bulan')
    ->take(6)
    ->get();

$sppPerBulan = PembayaranSPP::selectRaw(
        'DATE_FORMAT(tanggal_bayar, "%Y-%m") as bulan, SUM(biaya_pokok) as total'
    )
    ->groupByRaw('DATE_FORMAT(tanggal_bayar, "%Y-%m")')   // pakai ekspresi yang sama
    ->orderBy('bulan')
    ->take(6)
    ->get();

    return view('tatausaha.dashboard', compact(
        'userName',
        'totalPegawai',
        'totalSiswa',
        'totalNominalGaji',
        'totalNominalSPP',
        'jumlahPresensi',
        'gajiPerBulan',
        'sppPerBulan',
    ));
}

// =======================
// PENGGAJIAN
// =======================
public function indexGaji()
{
    $gajis = Gaji::with('pegawai')->latest()->get();

    return view('tatausaha.gaji.index', compact('gajis'));
}

// form create
public function createGaji()
{
    $pegawai         = Pegawai::orderBy('nama')->get();
    $noFakturDefault = method_exists(Gaji::class, 'generateNoFaktur')
        ? Gaji::generateNoFaktur()
        : null;

    return view('tatausaha.gaji.create', compact('pegawai', 'noFakturDefault'));
}

// simpan data baru (hitung otomatis dari presensi, proporsional)
public function storeGaji(Request $request)
{
    $data = $request->validate([
        'no_faktur'  => 'required|string|max:100',
        'id_pegawai' => 'required|exists:pegawai,id',
        'tahun_gaji' => 'required|integer',
        'bulan_gaji' => 'required|string',
        'tgl_gaji'   => 'required|date',
    ]);

    // 1. ambil pegawai + gaji pokok bulanan
    $pegawai = Pegawai::findOrFail($data['id_pegawai']);

    // 2. mapping nama bulan ke angka (1–12)
    $mapBulan = [
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
    ];
    $bulanAngka = $mapBulan[$data['bulan_gaji']] ?? null;

    // 3. hitung jumlah hadir pada bulan & tahun tersebut
    $jumlahHadir = Presensi::where('id_pegawai', $pegawai->id)
        ->whereYear('tgl_presensi', $data['tahun_gaji'])
        ->when($bulanAngka, function ($q) use ($bulanAngka) {
            $q->whereMonth('tgl_presensi', $bulanAngka);
        })
        ->where('status_presensi', 'hadir')
        ->count(); // [web:553][web:561]

    // 4. hitung jumlah hari kerja bulan itu (misal Senin–Jumat)
    $startOfMonth = Carbon::create($data['tahun_gaji'], $bulanAngka, 1);
    $endOfMonth   = $startOfMonth->copy()->endOfMonth();

    $jumlahHariKerja = 0;
    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
        if ($date->isWeekday()) { // Senin–Jumat dianggap hari kerja
            $jumlahHariKerja++;
        }
    }

    // 5. total gaji proporsional: gaji_pokok * (hadir / hari_kerja)
    if ($jumlahHariKerja === 0) {
        $totalGaji = 0;
    } else {
        $totalGaji = round($pegawai->gaji_pokok * ($jumlahHadir / $jumlahHariKerja));
    }

    // 6. simpan
    Gaji::create([
        'no_faktur'    => $data['no_faktur'],
        'id_pegawai'   => $pegawai->id,
        'tahun_gaji'   => $data['tahun_gaji'],
        'bulan_gaji'   => $data['bulan_gaji'],
        'tgl_gaji'     => $data['tgl_gaji'],
        'jumlah_hadir' => $jumlahHadir,
        'total_gaji'   => $totalGaji,
    ]);

    return redirect()->route('tatausaha.gaji.index')->with('success', 'Data gaji tersimpan.');
}

// form edit
public function editGaji(Gaji $gaji)
{
    $pegawai = Pegawai::orderBy('nama')->get();

    return view('tatausaha.gaji.edit', compact('gaji', 'pegawai'));
}

// update data (hitung ulang proporsional)
public function updateGaji(Request $request, Gaji $gaji)
{
    $data = $request->validate([
        'no_faktur'  => 'required|string|max:100',
        'id_pegawai' => 'required|exists:pegawai,id',
        'tahun_gaji' => 'required|integer',
        'bulan_gaji' => 'required|string',
        'tgl_gaji'   => 'required|date',
    ]);

    $pegawai = Pegawai::findOrFail($data['id_pegawai']);

    $mapBulan = [
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
    ];
    $bulanAngka = $mapBulan[$data['bulan_gaji']] ?? null;

    $jumlahHadir = Presensi::where('id_pegawai', $pegawai->id)
        ->whereYear('tgl_presensi', $data['tahun_gaji'])
        ->when($bulanAngka, function ($q) use ($bulanAngka) {
            $q->whereMonth('tgl_presensi', $bulanAngka);
        })
        ->where('status_presensi', 'hadir')
        ->count(); // [web:553][web:561]

    $startOfMonth = Carbon::create($data['tahun_gaji'], $bulanAngka, 1);
    $endOfMonth   = $startOfMonth->copy()->endOfMonth();

    $jumlahHariKerja = 0;
    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
        if ($date->isWeekday()) {
            $jumlahHariKerja++;
        }
    }

    if ($jumlahHariKerja === 0) {
        $totalGaji = 0;
    } else {
        $totalGaji = round($pegawai->gaji_pokok * ($jumlahHadir / $jumlahHariKerja));
    }

    $gaji->update([
        'no_faktur'    => $data['no_faktur'],
        'id_pegawai'   => $pegawai->id,
        'tahun_gaji'   => $data['tahun_gaji'],
        'bulan_gaji'   => $data['bulan_gaji'],
        'tgl_gaji'     => $data['tgl_gaji'],
        'jumlah_hadir' => $jumlahHadir,
        'total_gaji'   => $totalGaji,
    ]);

    return redirect()->route('tatausaha.gaji.index')->with('success', 'Data gaji diperbarui.');
}

// hapus data tetap sama
public function destroyGaji(Gaji $gaji)
{
    $gaji->delete();

    return redirect()->route('tatausaha.gaji.index')->with('success', 'Data gaji dihapus.');
}

    // =======================
    // PEMBAYARAN SPP
    // =======================
public function indexSpp()
{
    $spps = PembayaranSPP::with('siswa')->latest()->get();

    return view('tatausaha.spp.index', compact('spps'));
}

public function createSpp()
{
    $siswa = Siswa::orderBy('nama_lengkap')->get();

    return view('tatausaha.spp.create', compact('siswa'));
}

public function storeSpp(Request $request)
{
    $data = $request->validate([
        'siswa_id'    => 'required|exists:siswa,id',
        'Kelas'       => 'required|string|max:255',
        'komponen_id' => 'required|exists:komponen_biaya_daftar,id',
        'nominal'     => 'required|numeric',
        'tanggal'     => 'required|date',
    ]);

    Pendaftaran::create($data);


    return redirect()->route('tatausaha.spp.index')->with('success', 'Pembayaran SPP tersimpan.');
}

public function editSpp(PembayaranSPP $spp)
{
    $siswa = Siswa::orderBy('nama_lengkap')->get();

    return view('tatausaha.spp.edit', compact('spp', 'siswa'));
}

public function updateSpp(Request $request, PembayaranSPP $spp)
{
    $data = $request->validate([
        'nis'           => 'required|exists:siswa,nis',
        'tanggal_bayar' => 'required|date',
        'bulan'         => 'required|string',
        'tahun'         => 'required|string',
        'biaya_pokok'   => 'required|numeric',
    ]);

    $spp->update($data);

    return redirect()->route('tatausaha.spp.index')->with('success', 'Pembayaran SPP diperbarui.');
}

public function destroySpp(PembayaranSPP $spp)
{
    $spp->delete();

    return redirect()->route('tatausaha.spp.index')->with('success', 'Pembayaran SPP dihapus.');
}

    // =======================
    // PENDAFTARAN
    // =======================
public function indexPendaftaran()
{
     $pendaftarans = Pendaftaran::with(['siswaRef', 'komponenRef'])->latest()->get();

    return view('tatausaha.pendaftaran.index', compact('pendaftarans'));
}

// FORM CREATE
public function createPendaftaran()
{
    $siswa     = Siswa::orderBy('nama_lengkap')->get();
    $komponens = KomponenBiayaDaftar::orderBy('nama_komponen')->get();

    return view('tatausaha.pendaftaran.create', compact('siswa', 'komponens'));
}

// SIMPAN
public function storePendaftaran(Request $request)
{
    $data = $request->validate([
        'siswa'          => 'required',      // atau 'siswa_id' kalau pakai nama itu
        'Kelas'          => 'required|string|max:255',
        'komponen_biaya' => 'required',
        'nominal'        => 'required|numeric',
        'tanggal'        => 'required|date',
    ]);

    Pendaftaran::create($data);

    // redirect ke halaman index pendaftaran (GET)
    return redirect()
        ->route('tatausaha.pendaftaran.index')
        ->with('success', 'Data pendaftaran tersimpan.');
}

// FORM EDIT
public function editPendaftaran(Pendaftaran $pendaftaran)
{
    $siswa     = Siswa::orderBy('nama_lengkap')->get();
    $komponens = KomponenBiayaDaftar::orderBy('nama_komponen')->get();

    return view('tatausaha.pendaftaran.edit', compact('pendaftaran', 'siswa', 'komponens'));
}

// UPDATE
public function updatePendaftaran(Request $request, Pendaftaran $pendaftaran)
{
    $data = $request->validate([
        'siswa'          => 'required|string|max:255',
        'Kelas'          => 'required|string|max:255',
        'komponen_biaya' => 'required|string|max:255',
        'nominal'        => 'required|numeric',
        'tanggal'        => 'required|date',
    ]);

    $pendaftaran->update($data);

    return redirect()->route('tatausaha.pendaftaran.index')
        ->with('success', 'Data pendaftaran diperbarui.');
}

// HAPUS
public function destroyPendaftaran(Pendaftaran $pendaftaran)
{
    $pendaftaran->delete();

    return redirect()->route('tatausaha.pendaftaran.index')
        ->with('success', 'Data pendaftaran dihapus.');
}

    // =======================
    // DATA PEGAWAI
    // =======================
public function indexPegawai()
{
    $pegawai = Pegawai::with('jabatan')->orderBy('nama')->get();

    return view('tatausaha.pegawai.index', compact('pegawai'));
}

// FORM CREATE
public function createPegawai()
{
    $jabatans = Jabatan::orderBy('nama_jabatan')->get();

    return view('tatausaha.pegawai.create', compact('jabatans'));
}

// SIMPAN
public function storePegawai(Request $request)
{
    $data = $request->validate([
        'nip'        => 'nullable|string|max:20',
        'nama'       => 'required|string|max:255',
        'jabatan_id' => 'nullable|exists:jabatans,id',
        'gender'     => 'required|in:Laki-laki,Perempuan',
        'gaji_pokok' => 'required|string|max:255',
    ]);

    Pegawai::create($data);

    return redirect()->route('tatausaha.pegawai.index')
        ->with('success', 'Pegawai tersimpan.');
}

// FORM EDIT
public function editPegawai(Pegawai $pegawai)
{
    $jabatans = Jabatan::orderBy('nama_jabatan')->get();

    return view('tatausaha.pegawai.edit', compact('pegawai', 'jabatans'));
}

// UPDATE
public function updatePegawai(Request $request, Pegawai $pegawai)
{
    $data = $request->validate([
        'nip'        => 'nullable|string|max:20',
        'nama'       => 'required|string|max:255',
        'jabatan_id' => 'nullable|exists:jabatans,id',
        'gender'     => 'required|in:Laki-laki,Perempuan',
        'gaji_pokok' => 'required|string|max:255',
    ]);

    $pegawai->update($data);

    return redirect()->route('tatausaha.pegawai.index')
        ->with('success', 'Pegawai diperbarui.');
}

// HAPUS
public function destroyPegawai(Pegawai $pegawai)
{
    $pegawai->delete();

    return redirect()->route('tatausaha.pegawai.index')
        ->with('success', 'Pegawai dihapus.');
}
    // =======================
    // DATA SISWA
    // =======================
// LIST
public function indexSiswa()
{
    $siswa = Siswa::orderBy('nama_lengkap')->get();

    return view('tatausaha.siswa.index', compact('siswa'));
}

// FORM CREATE
public function createSiswa()
{
    return view('tatausaha.siswa.create');
}

// SIMPAN
public function storeSiswa(Request $request)
{
    $data = $request->validate([
        'nis'          => 'required|string|max:50|unique:siswa,nis',
        'nama_lengkap' => 'required|string|max:255',
        'kelas'        => 'required|string|max:50',
        'status'       => 'required|in:Aktif,Tidak Aktif',
        'jenis_kelamin'=> 'required|in:Laki-laki,Perempuan',
    ]);

    Siswa::create($data);

    return redirect()->route('tatausaha.siswa.index')
        ->with('success', 'Siswa tersimpan.');
}

// FORM EDIT
public function editSiswa(Siswa $siswa)
{
    return view('tatausaha.siswa.edit', compact('siswa'));
}

// UPDATE
public function updateSiswa(Request $request, Siswa $siswa)
{
    $data = $request->validate([
        'nis'          => 'required|string|max:50|unique:siswa,nis,' . $siswa->id,
        'nama_lengkap' => 'required|string|max:255',
        'kelas'        => 'required|string|max:50',
        'status'       => 'required|in:Aktif,Tidak Aktif',
        'jenis_kelamin'=> 'required|in:Laki-laki,Perempuan',
    ]);

    $siswa->update($data);

    return redirect()->route('tatausaha.siswa.index')
        ->with('success', 'Siswa diperbarui.');
}

// HAPUS
public function destroySiswa(Siswa $siswa)
{
    $siswa->delete();

    return redirect()->route('tatausaha.siswa.index')
        ->with('success', 'Siswa dihapus.');
}

    // =======================
    // CHART OF ACCOUNT
    // =======================
// LIST
public function indexCoa()
{
    $coas = Coa::orderBy('no_akun')->get();

    return view('tatausaha.coa.index', compact('coas'));
}

// FORM CREATE
public function createCoa()
{
    return view('tatausaha.coa.create');
}

// SIMPAN
public function storeCoa(Request $request)
{
    $data = $request->validate([
        'header_akun' => 'required|integer',
        'no_akun'     => 'required|string|max:10|unique:coa,no_akun',
        'nama_akun'   => 'required|string|max:255',
    ]);

    Coa::create($data);

    return redirect()->route('tatausaha.coa.index')
        ->with('success', 'Akun berhasil ditambahkan.');
}

// FORM EDIT
public function editCoa(Coa $coa)
{
    return view('tatausaha.coa.edit', compact('coa'));
}

// UPDATE
public function updateCoa(Request $request, Coa $coa)
{
    $data = $request->validate([
        'header_akun' => 'required|integer',
        'no_akun'     => 'required|string|max:10|unique:coa,no_akun,' . $coa->id,
        'nama_akun'   => 'required|string|max:255',
    ]);

    $coa->update($data);

    return redirect()->route('tatausaha.coa.index')
        ->with('success', 'Akun berhasil diperbarui.');
}

// HAPUS
public function destroyCoa(Coa $coa)
{
    $coa->delete();

    return redirect()->route('tatausaha.coa.index')
        ->with('success', 'Akun berhasil dihapus.');
}
public function indexPresensi()
{
    $presensis = Presensi::with('pegawai')
        ->orderByDesc('tgl_presensi')
        ->paginate(20); // <— ganti get() jadi paginate()

    return view('tatausaha.presensi.index', compact('presensis'));
}

// CREATE
public function createPresensi()
{
    $pegawai = Pegawai::orderBy('nama')->get();

    return view('tatausaha.presensi.create', compact('pegawai'));
}

// STORE
public function storePresensi(Request $request)
{
    $data = $request->validate([
        'id_pegawai'     => 'required|exists:pegawai,id',
        'tgl_presensi'   => 'required|date',
        'waktu_masuk'    => 'required|date_format:H:i',
        'waktu_keluar'   => 'nullable|date_format:H:i',
        'status_presensi'=> 'required|in:hadir,sakit,izin,alfa',
    ]);

    Presensi::create($data);

    return redirect()->route('tatausaha.presensi.index')
        ->with('success', 'Presensi tersimpan.');
}

// EDIT
public function editPresensi(Presensi $presensi)
{
    $pegawai = Pegawai::orderBy('nama')->get();

    return view('tatausaha.presensi.edit', compact('presensi', 'pegawai'));
}

// UPDATE
public function updatePresensi(Request $request, Presensi $presensi)
{
    $data = $request->validate([
        'id_pegawai'     => 'required|exists:pegawai,id',
        'tgl_presensi'   => 'required|date',
        'waktu_masuk'    => 'required|date_format:H:i',
        'waktu_keluar'   => 'nullable|date_format:H:i',
        'status_presensi'=> 'required|in:hadir,sakit,izin,alfa',
    ]);

    $presensi->update($data);

    return redirect()->route('tatausaha.presensi.index')
        ->with('success', 'Presensi diperbarui.');
}

// DESTROY
public function destroyPresensi(Presensi $presensi)
{
    $presensi->delete();

    return redirect()->route('tatausaha.presensi.index')
        ->with('success', 'Presensi dihapus.');
}
}
