@extends('layouts.tatausaha')

@section('title', 'Edit Siswa')

@section('content')
    <h3 class="mb-3">Edit Siswa</h3>

    <form method="POST" action="{{ route('tatausaha.siswa.update', $siswa) }}" class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">NIS</label>
            <input type="text" name="nis" class="form-control"
                   value="{{ old('nis', $siswa->nis) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control"
                   value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <input type="text" name="kelas" class="form-control"
                   value="{{ old('kelas', $siswa->kelas) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Aktif" {{ old('status', $siswa->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ old('status', $siswa->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select" required>
                <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Siswa</button>
    </form>
@endsection
