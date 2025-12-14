@extends('layouts.tatausaha')

@section('title', 'Edit Presensi')

@section('content')
    <h3 class="mb-3">Edit Presensi Pegawai</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('tatausaha.presensi.update', $presensi) }}"
          class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pegawai</label>
            <select name="id_pegawai" class="form-select" required>
                <option value="">- Pilih Pegawai -</option>
                @foreach($pegawai as $pg)
                    <option value="{{ $pg->id }}"
                        {{ old('id_pegawai', $presensi->id_pegawai) == $pg->id ? 'selected' : '' }}>
                        {{ $pg->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Presensi</label>
            <input type="date" name="tgl_presensi" class="form-control" required
                   value="{{ old('tgl_presensi', $presensi->tgl_presensi) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu Masuk</label>
            <input type="time" name="waktu_masuk" class="form-control" required
                   value="{{ old('waktu_masuk', $presensi->waktu_masuk) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu Keluar</label>
            <input type="time" name="waktu_keluar" class="form-control"
                   value="{{ old('waktu_keluar', $presensi->waktu_keluar) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status Presensi</label>
            @php $st = old('status_presensi', $presensi->status_presensi); @endphp
            <select name="status_presensi" class="form-select" required>
                <option value="">- Pilih Status -</option>
                <option value="hadir" {{ $st == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="sakit" {{ $st == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="izin"  {{ $st == 'izin'  ? 'selected' : '' }}>Izin</option>
                <option value="alfa"  {{ $st == 'alfa'  ? 'selected' : '' }}>Alfa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Presensi</button>
    </form>
@endsection
