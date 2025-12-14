@extends('layouts.tatausaha')

@section('title', 'Tambah Gaji')

@section('content')
    <h3 class="mb-3">Tambah Gaji</h3>

    <form method="POST" action="{{ route('tatausaha.gaji.store') }}" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="mb-3">
            <label class="form-label">No Faktur</label>
            <input type="text" name="no_faktur" class="form-control"
                   value="{{ old('no_faktur', $noFakturDefault) }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Pegawai</label>
            <select name="id_pegawai" class="form-select">
                @foreach($pegawai as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun_gaji" class="form-control"
                       value="{{ old('tahun_gaji', now()->year) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bulan</label>
                <select name="bulan_gaji" class="form-select">
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                        <option value="{{ $b }}">{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Tanggal Gaji</label>
                <input type="date" name="tgl_gaji" class="form-control"
                       value="{{ old('tgl_gaji', now()->toDateString()) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah Hadir</label>
            <input type="number" name="jumlah_hadir" class="form-control"
                value="{{ old('jumlah_hadir') }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Gaji</label>
            <input type="number" name="total_gaji" class="form-control"
                value="{{ old('total_gaji') }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Gaji</button>
    </form>
@endsection
