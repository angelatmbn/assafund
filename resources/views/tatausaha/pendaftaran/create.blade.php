@extends('layouts.tatausaha')

@section('title', 'Tambah Pendaftaran')

@section('content')
    <h3 class="mb-3">Tambah Pendaftaran</h3>

    <form method="POST" action="{{ route('tatausaha.pendaftaran.store') }}" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="mb-3">
                        <label class="form-label">Nama Siswa & Kelas</label>
                        <select name="siswa" class="form-select" id="select-siswa" required>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}"
                                {{ old('siswa') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama_lengkap }} - {{ $s->kelas }}
                            </option>
                        @endforeach
                    </select>
        </div>

        <div class="mb-3 mt-2">
    <label class="form-label">Kelas</label>
    <input type="text" name="Kelas" id="input-kelas" class="form-control"
           value="{{ old('Kelas') }}" readonly>
</div>


<div class="mb-3">
    <label class="form-label">Komponen Biaya</label>
    <select name="komponen_biaya" class="form-select" id="select-komponen" required>
        @foreach($komponens as $k)
            <option value="{{ $k->nama_komponen }}"
                    data-nominal="{{ $k->nominal }}"
                {{ old('komponen_biaya') == $k->nama_komponen ? 'selected' : '' }}>
                {{ $k->nama_komponen }} - {{ 'Rp ' . number_format($k->nominal, 0, ',', '.') }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Nominal (Rp)</label>
    <input type="number" name="nominal" id="input-nominal" class="form-control"
           value="{{ old('nominal') }}" required>
</div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ old('tanggal', now()->toDateString()) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pendaftaran</button>
    </form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('select-siswa');
    const inputKelas = document.getElementById('input-kelas');

    const mapKelas = {
        @foreach($siswa as $s)
        "{{ $s->id }}": "{{ $s->kelas }}",
        @endforeach
    };

    function syncKelas() {
        const id = select.value;
        inputKelas.value = mapKelas[id] ?? '';
    }

    select.addEventListener('change', syncKelas);
    syncKelas();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectKomponen = document.getElementById('select-komponen');
    const inputNominal   = document.getElementById('input-nominal');

    function syncNominal() {
        const opt = selectKomponen.options[selectKomponen.selectedIndex];
        const nominal = opt.getAttribute('data-nominal') || '';
        inputNominal.value = nominal;
    }

    selectKomponen.addEventListener('change', syncNominal);
    syncNominal(); // set awal
});
</script>


@endsection
