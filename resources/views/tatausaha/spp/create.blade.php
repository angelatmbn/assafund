@extends('layouts.tatausaha')

@section('title', 'Tambah Pembayaran SPP')

@section('content')
    <h3 class="mb-3">Tambah Pembayaran SPP</h3>

    <form method="POST" action="{{ route('tatausaha.spp.store') }}" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="mb-3">
            <label class="form-label">Siswa</label>
            <select name="nis" class="form-select" required>
                @foreach($siswa as $s)
                    <option value="{{ $s->nis }}">
                        {{ $s->nis }} - {{ $s->nama_lengkap }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" class="form-control"
                       value="{{ old('tanggal_bayar', now()->toDateString()) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select" required>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                        <option value="{{ $b }}" {{ old('bulan') == $b ? 'selected' : '' }}>
                            {{ $b }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control"
                       value="{{ old('tahun', now()->year) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Biaya Pokok</label>
            <input type="number" name="biaya_pokok" class="form-control"
                   value="{{ old('biaya_pokok') }}" required placeholder="Masukan Biaya Pokok">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
    </form>
@endsection
