@extends('layouts.tatausaha')

@section('title', 'Edit Pembayaran SPP')

@section('content')
    <h3 class="mb-3">Edit Pembayaran SPP</h3>

    <form method="POST" action="{{ route('tatausaha.spp.update', $spp) }}" class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Siswa</label>
            <select name="nis" class="form-select">
                @foreach($siswa as $s)
                    <option value="{{ $s->nis }}"
                        {{ old('nis', $spp->nis) == $s->nis ? 'selected' : '' }}>
                        {{ $s->nis }} - {{ $s->nama_lengkap }} ({{ $s->kelas }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" class="form-control"
                       value="{{ old('tanggal_bayar', $spp->tanggal_bayar) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                        <option value="{{ $b }}"
                            {{ old('bulan', $spp->bulan) == $b ? 'selected' : '' }}>
                            {{ $b }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Tahun</label>
                <input type="text" name="tahun" class="form-control"
                       value="{{ old('tahun', $spp->tahun) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Biaya Pokok (Rp)</label>
            <input type="number" name="biaya_pokok" class="form-control"
                   value="{{ old('biaya_pokok', $spp->biaya_pokok) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Pembayaran</button>
    </form>
@endsection
