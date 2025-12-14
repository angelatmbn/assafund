@extends('layouts.tatausaha')

@section('title', 'Edit Pendaftaran')

@section('content')
    <h3 class="mb-3">Edit Pendaftaran</h3>

    <form method="POST" action="{{ route('tatausaha.pendaftaran.update', $pendaftaran) }}"
          class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Siswa & Kelas</label>
            <select name="siswa" class="form-select" id="select-siswa" required>
                @foreach($siswa as $s)
                    <option value="{{ $s->nama_lengkap }}"
                        {{ old('siswa', $pendaftaran->siswa) == $s->nama_lengkap ? 'selected' : '' }}>
                        {{ $s->nama_lengkap }} - {{ $s->kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <input type="text" name="Kelas" id="input-kelas" class="form-control"
                   value="{{ old('Kelas', $pendaftaran->Kelas) }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Komponen Biaya</label>
            <select name="komponen_biaya" class="form-select" required>
                @foreach($komponens as $k)
                    <option value="{{ $k->nama_komponen }}"
                        {{ old('komponen_biaya', $pendaftaran->komponen_biaya) == $k->nama_komponen ? 'selected' : '' }}>
                        {{ $k->nama_komponen }} ({{ 'Rp ' . number_format($k->nominal, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nominal (Rp)</label>
            <input type="number" name="nominal" class="form-control"
                   value="{{ old('nominal', $pendaftaran->nominal) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ old('tanggal', $pendaftaran->tanggal) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Pendaftaran</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('select-siswa');
            const inputKelas = document.getElementById('input-kelas');

            const mapKelas = {
                @foreach($siswa as $s)
                "{{ $s->nama_lengkap }}": "{{ $s->kelas }}",
                @endforeach
            };

            function syncKelas() {
                const nama = select.value;
                inputKelas.value = mapKelas[nama] ?? '';
            }

            select.addEventListener('change', syncKelas);
            syncKelas();
        });
    </script>
@endsection
