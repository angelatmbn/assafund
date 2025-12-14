{{-- resources/views/guru/siswa/create.blade.php --}}
@extends('layouts.guru')

@section('title', 'Tambah Siswa')

@section('content')
    <h3 class="mb-3">Tambah Siswa</h3>

    <form method="POST" action="{{ route('guru.siswa.store') }}" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">NIS</label>
            <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
                <option value="Tidak Aktif">Drop Out</option>
                <option value="Tidak Aktif">Lulus</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection
