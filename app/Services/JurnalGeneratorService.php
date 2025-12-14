<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Pendaftaran;
use App\Models\PembayaranSPP;
use App\Models\Gaji;
use App\Models\Jurnal;
use App\Models\JurnalDetail;

class JurnalGeneratorService
{
    // mapping no_akun contoh, sesuaikan dengan tabel akun kamu
    protected int $akunKas                = 101; // Kas / Bank
    protected int $akunPiutangSPP         = 113;
    protected int $akunPendapatanPendaftaran = 401;
    protected int $akunPendapatanSPP      = 402;
    protected int $akunBebanGaji          = 501;
    protected int $akunUtangGaji          = 211;

    /**
     * Jurnal dari pendaftaran siswa (pemasukan di muka).
     */
    public function fromPendaftaran(Pendaftaran $p): void
    {
        DB::transaction(function () use ($p) {

            $jurnal = Jurnal::create([
                'tgl'          => $p->tanggal,
                'no_referensi' => 'PENDAFTARAN-'.$p->id,
                'deskripsi'    => 'Pendaftaran '.$p->komponen_biaya,
            ]);

            // Debit Kas
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'no_akun'   => $this->akunKas,
                'deskripsi' => 'Kas - Pendaftaran '.$p->komponen_biaya,
                'debit'     => $p->nominal,
                'credit'    => 0,
            ]);

            // Kredit Pendapatan Pendaftaran
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'no_akun'   => $this->akunPendapatanPendaftaran,
                'deskripsi' => 'Pendapatan Pendaftaran '.$p->komponen_biaya,
                'debit'     => 0,
                'credit'    => $p->nominal,
            ]);
        });
    }

    /**
     * Jurnal dari pembayaran SPP (pelunasan piutang SPP atau langsung kas → pendapatan).
     */
    public function fromPembayaranSPP(PembayaranSPP $bayar): void
    {
        DB::transaction(function () use ($bayar) {

            $jurnal = Jurnal::create([
                'tgl'          => $bayar->tanggal_bayar,
                'no_referensi' => 'SPP-'.$bayar->id,
                'deskripsi'    => 'Pembayaran SPP '.$bayar->nis.' '.$bayar->bulan.' '.$bayar->tahun,
            ]);

            // Debit Kas
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'no_akun'   => $this->akunKas,
                'deskripsi' => 'Kas - Pembayaran SPP',
                'debit'     => $bayar->jumlah_bayar,
                'credit'    => 0,
            ]);

            // Kredit Pendapatan SPP (atau piutang, sesuaikan kebijakanmu)
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'no_akun'   => $this->akunPendapatanSPP,
                'deskripsi' => 'Pendapatan SPP',
                'debit'     => 0,
                'credit'    => $bayar->jumlah_bayar,
            ]);
        });
    }

    /**
     * Jurnal dari penggajian.
     */
    public function fromGaji(Gaji $gaji): void
    {
        DB::transaction(function () use ($gaji) {

            $jurnal = Jurnal::create([
                'tgl'          => $gaji->tanggal_pembayaran,
                'no_referensi' => $gaji->no_faktur,
                'deskripsi'    => 'Penggajian pegawai ID '.$gaji->id_pegawai,
            ]);

            // Debit Beban Gaji
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'no_akun'   => $this->akunBebanGaji,
                'deskripsi' => 'Beban gaji pegawai',
                'debit'     => $gaji->total_gaji,
                'credit'    => 0,
            ]);

            // Kredit Kas (atau utang gaji kalau belum dibayar tunai)
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'no_akun'   => $this->akunKas, // atau $this->akunUtangGaji
                'deskripsi' => 'Kas - Pembayaran gaji',
                'debit'     => 0,
                'credit'    => $gaji->total_gaji,
            ]);
        });
    }
}
