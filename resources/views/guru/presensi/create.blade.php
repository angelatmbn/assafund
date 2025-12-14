@extends('layouts.guru')

@section('title', 'Tambah Presensi')

@section('content')
    <h3 class="mb-3">Tambah Presensi</h3>

    <form method="POST" action="{{ route('guru.presensi.store') }}" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="mb-3">
            <label class="form-label">Pegawai</label>
            <select name="id_pegawai" class="form-select">
                @foreach($pegawai as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Presensi</label>
            <input type="date" name="tgl_presensi" class="form-control"
                   value="{{ old('tgl_presensi', now()->toDateString()) }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Waktu Masuk</label>
                <input type="time" name="waktu_masuk" class="form-control"
                       value="{{ old('waktu_masuk', now()->format('H:i')) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Waktu Keluar</label>
                <input type="time" name="waktu_keluar" class="form-control"
                       value="{{ old('waktu_keluar', '23:59') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Status Presensi</label>
            <select name="status_presensi" class="form-select">
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alfa">Alfa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Presensi</button>
    </form>
@endsection
