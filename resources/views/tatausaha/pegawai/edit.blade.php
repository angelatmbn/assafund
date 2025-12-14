@extends('layouts.tatausaha')

@section('title', 'Edit Pegawai')

@section('content')
    <h3 class="mb-3">Edit Pegawai</h3>

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
          action="{{ route('tatausaha.pegawai.update', $pegawai) }}"
          class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">NIP</label>
            <input type="text" name="nip" class="form-control"
                   value="{{ old('nip', $pegawai->nip) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Pegawai</label>
            <input type="text" name="nama" class="form-control" required
                   value="{{ old('nama', $pegawai->nama) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select name="jabatan_id" class="form-select" id="select-jabatan">
                <option value="">- Tanpa Jabatan -</option>
                @foreach($jabatans as $j)
                    <option value="{{ $j->id }}"
                            data-gaji="{{ $j->gaji_pokok }}"
                        {{ old('jabatan_id', $pegawai->jabatan_id) == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_jabatan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Gaji Pokok</label>
            <input type="text" name="gaji_pokok" id="input-gaji" class="form-control" required
                   value="{{ old('gaji_pokok', $pegawai->gaji_pokok) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            @php $g = old('gender', $pegawai->gender); @endphp
            <select name="gender" class="form-select" required>
                <option value="">- Pilih Gender -</option>
                <option value="Laki-laki" {{ $g == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ $g == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Pegawai</button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectJabatan = document.getElementById('select-jabatan');
        const inputGaji     = document.getElementById('input-gaji');

        function syncGaji() {
            const opt  = selectJabatan.options[selectJabatan.selectedIndex];
            const gaji = opt ? opt.getAttribute('data-gaji') : '';
            if (gaji) {
                inputGaji.value = gaji;
            }
        }

        selectJabatan.addEventListener('change', syncGaji);
        syncGaji(); // set awal sesuai jabatan pegawai
    });
    </script>
@endsection
