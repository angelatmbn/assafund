@extends('layouts.tatausaha')

@section('title', 'Laporan Bulanan')

@section('content')
    <h3 class="mb-2">
        Laporan Keuangan Bulan
        {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
    </h3>

    <a href="{{ route('tatausaha.laporan.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        &laquo; Ganti Periode
    </a>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="text-muted text-uppercase mb-2" style="font-size:11px;">
                Ringkasan Otomatis
            </h6>
            <p class="mb-0">
                {{ $ringkasan }}
            </p>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Total Gaji</div>
                    <h4 class="mb-1">Rp {{ number_format($totalGaji, 0, ',', '.') }}</h4>
                    <small class="text-muted">{{ $jumlahTransaksiGaji }} transaksi</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Total SPP</div>
                    <h4 class="mb-1">Rp {{ number_format($totalSPP, 0, ',', '.') }}</h4>
                    <small class="text-muted">{{ $jumlahTransaksiSPP }} transaksi</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Total Pendaftaran</div>
                    <h4 class="mb-1">Rp {{ number_format($totalPendaftaran, 0, ',', '.') }}</h4>
                    <small class="text-muted">{{ $jumlahPendaftaran }} transaksi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Rekap Kas Bulanan</div>
            <table class="table table-sm mb-0">
                <tbody>
                <tr>
                    <td style="width:40%;">Total Pemasukan (SPP + Pendaftaran)</td>
                    <td>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Pengeluaran Gaji</td>
                    <td>Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Saldo Bersih Bulan Ini</strong></td>
                    <td>
                        <strong>
                            Rp {{ number_format($saldoBersih, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="text-muted text-uppercase mb-2" style="font-size:11px;">
            Tanya AI tentang Laporan Ini
        </h6>

        <form method="POST"
              action="{{ route('tatausaha.laporan.ask', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
            @csrf
            <div class="mb-2">
                <textarea name="question" class="form-control" rows="2"
                          placeholder="Contoh: Pengeluaran apa yang paling besar bulan ini?">{{ old('question') }}</textarea>
            </div>
            <button class="btn btn-sm btn-success">Tanya AI</button>
        </form>

        @isset($jawabanAi)
            <hr>
            <div>
                <strong>Jawaban AI:</strong>
                <p class="mb-0">{{ $jawabanAi }}</p>
            </div>
        @endisset
    </div>
</div>

@endsection
