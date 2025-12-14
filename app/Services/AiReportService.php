<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiReportService
{
    public function summarize(array $data): string
    {
        // siapkan prompt ringkas berbahasa Indonesia
        $prompt = $this->buildPrompt($data);

        // contoh: panggil endpoint LLM kustom (ganti URL & header sesuai provider)
        $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.ai_reports.api_key'),
                'Accept'        => 'application/json',
            ])
            ->post(config('services.ai_reports.endpoint'), [
                'model'    => config('services.ai_reports.model', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Kamu adalah asisten keuangan sekolah. Buat ringkasan singkat dan jelas.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'max_tokens' => 250,
            ])
            ->json();

        // sesuaikan path dengan format respons provider
        return $response['choices'][0]['message']['content'] ?? 'Ringkasan tidak tersedia.';
    }

    protected function buildPrompt(array $d): string
    {
        // $d berisi month/year + angka agregat
        return sprintf(
            "Buat satu paragraf ringkasan keuangan sekolah untuk bulan %s %s. ".
            "Gunakan data berikut (dalam rupiah, tanpa menyebut titik ribuan di teks, cukup angka apa adanya):\n".
            "- Total gaji dibayarkan: %s rupiah, %s transaksi.\n".
            "- Total SPP diterima: %s rupiah, %s transaksi.\n".
            "- Total biaya pendaftaran: %s rupiah, %s transaksi.\n".
            "- Saldo bersih (pemasukan - gaji): %s rupiah.\n\n".
            "Gunakan bahasa Indonesia formal, satu paragraf saja, maksimal 4 kalimat.",
            $d['month_name'],
            $d['year'],
            $d['total_gaji'],
            $d['transaksi_gaji'],
            $d['total_spp'],
            $d['transaksi_spp'],
            $d['total_pendaftaran'],
            $d['transaksi_pendaftaran'],
            $d['saldo_bersih']
        );
    }

// app/Services/AiReportService.php

public function answerQuestion(array $data): string
{
    $q = strtolower($data['question']);

    // helper angka
    $fmt = fn($n) => 'Rp ' . number_format($n, 0, ',', '.');

    // 1. Pemasukan terbesar / terkecil / per jenis
    if (str_contains($q, 'pemasukan') || str_contains($q, 'pendapatan')) {

        // spesifik SPP
        if (str_contains($q, 'spp')) {
            return sprintf(
                'Pemasukan SPP bulan ini berjumlah %s dari %s transaksi.',
                $fmt($data['total_spp']),
                $data['transaksi_spp']
            );
        }

        // spesifik pendaftaran
        if (str_contains($q, 'pendaftaran')) {
            return sprintf(
                'Pemasukan dari pendaftaran siswa bulan ini berjumlah %s dari %s transaksi.',
                $fmt($data['total_pendaftaran']),
                $data['transaksi_pendaftaran']
            );
        }

        // bandingkan SPP vs pendaftaran
        if ($data['total_spp'] >= $data['total_pendaftaran']) {
            $selisih = $data['total_spp'] - $data['total_pendaftaran'];

            return sprintf(
                'Pemasukan terbesar bulan ini berasal dari SPP sebesar %s, sedangkan pendaftaran sebesar %s. Selisihnya sekitar %s.',
                $fmt($data['total_spp']),
                $fmt($data['total_pendaftaran']),
                $fmt($selisih)
            );
        }

        $selisih = $data['total_pendaftaran'] - $data['total_spp'];

        return sprintf(
            'Pemasukan terbesar bulan ini berasal dari pendaftaran sebesar %s, sedangkan SPP sebesar %s. Selisihnya sekitar %s.',
            $fmt($data['total_pendaftaran']),
            $fmt($data['total_spp']),
            $fmt($selisih)
        );
    }

    // 2. Pengeluaran / biaya gaji
    if (str_contains($q, 'pengeluaran') || str_contains($q, 'biaya') || str_contains($q, 'gaji')) {
        return sprintf(
            'Total pengeluaran gaji bulan ini adalah %s dari %s transaksi.',
            $fmt($data['total_gaji']),
            $data['transaksi_gaji']
        );
    }

    // 3. Rangkuman singkat
    if (str_contains($q, 'ringkas') || str_contains($q, 'summary') || str_contains($q, 'rangkuman')) {
        $arah = $data['saldo_bersih'] >= 0 ? 'surplus' : 'defisit';
        $abs  = abs($data['saldo_bersih']);

        return sprintf(
            'Bulan ini pemasukan total (SPP + pendaftaran) mencapai %s, pengeluaran gaji %s, sehingga kas mencatat %s sebesar %s.',
            $fmt($data['total_spp'] + $data['total_pendaftaran']),
            $fmt($data['total_gaji']),
            $arah,
            $fmt($abs)
        );
    }

    // 4. Saldo / kondisi keuangan
    if (str_contains($q, 'saldo') || str_contains($q, 'defisit') || str_contains($q, 'surplus') || str_contains($q, 'untung') || str_contains($q, 'rugi')) {
        $arah = $data['saldo_bersih'] >= 0 ? 'surplus (lebih besar pemasukan dibanding gaji)' : 'defisit (pengeluaran gaji lebih besar dari pemasukan)';
        $abs  = abs($data['saldo_bersih']);

        return sprintf(
            'Saldo bersih bulan ini adalah %s dengan nilai %s.',
            $fmt($abs),
            $arah
        );
    }

    // 5. Jumlah transaksi
    if (str_contains($q, 'berapa transaksi') || str_contains($q, 'jumlah transaksi')) {
        return sprintf(
            'Bulan ini terdapat %s transaksi gaji, %s transaksi SPP, dan %s transaksi pendaftaran.',
            $data['transaksi_gaji'],
            $data['transaksi_spp'],
            $data['transaksi_pendaftaran']
        );
    }

    // 6. Total pemasukan / pengeluaran / kas
    if (str_contains($q, 'total kas') || str_contains($q, 'total keuangan') || str_contains($q, 'total pemasukan')) {
        $pemasukan = $data['total_spp'] + $data['total_pendaftaran'];

        return sprintf(
            'Total pemasukan bulan ini (SPP + pendaftaran) adalah %s, dengan pengeluaran gaji %s.',
            $fmt($pemasukan),
            $fmt($data['total_gaji'])
        );
    }

    // default
    return 'Pertanyaan itu belum bisa dijawab otomatis. Coba gunakan kata kunci seperti: pemasukan, SPP, pendaftaran, pengeluaran, gaji, saldo, atau jumlah transaksi.';
}
}
