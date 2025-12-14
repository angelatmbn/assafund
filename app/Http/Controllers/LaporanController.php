<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\PembayaranSPP;
use App\Models\Pendaftaran;
use App\Services\AiReportService;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $bulan = now()->format('m');
        $tahun = now()->format('Y');

        return view('tatausaha.laporan.index', compact('bulan', 'tahun'));
    }

    public function generate(Request $request, AiReportService $aiReportService)
    {
        $data = $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        $bulan = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $tahun = (string) $data['tahun'];

        // hitung semua angka via helper
        $report = $this->buildReportData($bulan, $tahun);
        extract($report); // jadi $totalGaji, $totalSPP, dst

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $aiData = [
            'month'                 => $bulan,
            'month_name'            => $namaBulan,
            'year'                  => $tahun,
            'total_gaji'            => $totalGaji,
            'transaksi_gaji'        => $jumlahTransaksiGaji,
            'total_spp'             => $totalSPP,
            'transaksi_spp'         => $jumlahTransaksiSPP,
            'total_pendaftaran'     => $totalPendaftaran,
            'transaksi_pendaftaran' => $jumlahPendaftaran,
            'saldo_bersih'          => $saldoBersih,
        ];

        try {
            $ringkasan = $aiReportService->summarize($aiData);
        } catch (\Throwable $e) {
            $ringkasan = $this->generateSummary(
                $bulan,
                $tahun,
                $totalGaji,
                $jumlahTransaksiGaji,
                $totalSPP,
                $jumlahTransaksiSPP,
                $totalPendaftaran,
                $jumlahPendaftaran,
                $saldoBersih
            );
        }

        return view('tatausaha.laporan.hasil', compact(
            'bulan',
            'tahun',
            'totalGaji',
            'jumlahTransaksiGaji',
            'totalSPP',
            'jumlahTransaksiSPP',
            'totalPendaftaran',
            'jumlahPendaftaran',
            'totalPemasukan',
            'saldoBersih',
            'ringkasan'
        ));
    }

    /**
     * Endpoint tanya–jawab AI untuk laporan bulan tertentu.
     */
    public function ask($bulan, $tahun, Request $request, AiReportService $aiReportService)
    {
        $data = $request->validate([
            'question' => 'required|string|max:500',
        ]);

        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $tahun = (string) $tahun;

        // hitung lagi angka via helper
        $report = $this->buildReportData($bulan, $tahun);
        extract($report);

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $payload = [
            'month'                 => $bulan,
            'month_name'            => $namaBulan,
            'year'                  => $tahun,
            'total_gaji'            => $totalGaji,
            'transaksi_gaji'        => $jumlahTransaksiGaji,
            'total_spp'             => $totalSPP,
            'transaksi_spp'         => $jumlahTransaksiSPP,
            'total_pendaftaran'     => $totalPendaftaran,
            'transaksi_pendaftaran' => $jumlahPendaftaran,
            'saldo_bersih'          => $saldoBersih,
            'question'              => $data['question'],
        ];

        try {
            $jawabanAi = $aiReportService->answerQuestion($payload);
        } catch (\Throwable $e) {
            $jawabanAi = 'AI tidak dapat menjawab saat ini. Silakan coba lagi nanti.';
        }

        // ringkasan di atas halaman (boleh pakai summarize lagi atau fallback)
        try {
            $ringkasan = $aiReportService->summarize($payload);
        } catch (\Throwable $e) {
            $ringkasan = $this->generateSummary(
                $bulan,
                $tahun,
                $totalGaji,
                $jumlahTransaksiGaji,
                $totalSPP,
                $jumlahTransaksiSPP,
                $totalPendaftaran,
                $jumlahPendaftaran,
                $saldoBersih
            );
        }

        return view('tatausaha.laporan.hasil', compact(
            'bulan',
            'tahun',
            'totalGaji',
            'jumlahTransaksiGaji',
            'totalSPP',
            'jumlahTransaksiSPP',
            'totalPendaftaran',
            'jumlahPendaftaran',
            'totalPemasukan',
            'saldoBersih',
            'ringkasan',
            'jawabanAi'
        ));
    }

    /**
     * Fallback ringkasan non‑AI.
     */
    protected function generateSummary(
        $bulan,
        $tahun,
        $totalGaji,
        $jumlahTransaksiGaji,
        $totalSPP,
        $jumlahTransaksiSPP,
        $totalPendaftaran,
        $jumlahPendaftaran,
        $saldoBersih
    ) {
        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));

        $arahSaldo = $saldoBersih >= 0 ? 'surplus' : 'defisit';
        $saldoAbs  = abs($saldoBersih);

        return sprintf(
            'Pada bulan %s %s, total pembayaran gaji tercatat sebesar Rp %s dari %s transaksi, ' .
            'sementara pemasukan SPP mencapai Rp %s (%s transaksi) dan biaya pendaftaran siswa sebesar Rp %s (%s transaksi). ' .
            'Secara keseluruhan, posisi kas bulanan menunjukkan %s sebesar Rp %s.',
            $namaBulan,
            $tahun,
            number_format($totalGaji, 0, ',', '.'),
            $jumlahTransaksiGaji,
            number_format($totalSPP, 0, ',', '.'),
            $jumlahTransaksiSPP,
            number_format($totalPendaftaran, 0, ',', '.'),
            $jumlahPendaftaran,
            $arahSaldo,
            number_format($saldoAbs, 0, ',', '.')
        );
    }

    /**
     * Helper untuk menghitung agregat laporan.
     */
    private function buildReportData(string $bulan, string $tahun): array
    {
        $awal  = "{$tahun}-{$bulan}-01";
        $akhir = date('Y-m-t', strtotime($awal));

        $totalGaji           = Gaji::whereBetween('tgl_gaji', [$awal, $akhir])->sum('total_gaji');
        $jumlahTransaksiGaji = Gaji::whereBetween('tgl_gaji', [$awal, $akhir])->count();

        $totalSPP            = PembayaranSPP::whereBetween('tanggal_bayar', [$awal, $akhir])->sum('biaya_pokok');
        $jumlahTransaksiSPP  = PembayaranSPP::whereBetween('tanggal_bayar', [$awal, $akhir])->count();

        $totalPendaftaran    = Pendaftaran::whereBetween('tanggal', [$awal, $akhir])->sum('nominal');
        $jumlahPendaftaran   = Pendaftaran::whereBetween('tanggal', [$awal, $akhir])->count();

        $totalPemasukan = $totalSPP + $totalPendaftaran;
        $saldoBersih    = $totalPemasukan - $totalGaji;

        return compact(
            'totalGaji',
            'jumlahTransaksiGaji',
            'totalSPP',
            'jumlahTransaksiSPP',
            'totalPendaftaran',
            'jumlahPendaftaran',
            'totalPemasukan',
            'saldoBersih'
        );
    }
}
