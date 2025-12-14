@extends('layouts.tatausaha')

@section('content')
<h3>Input Penggajian</h3>

<form action="{{ route('tatausaha.gaji.store') }}" method="POST" class="card p-4">
    @csrf

    <div class="mb-3">
        <label class="form-label">No Faktur</label>
        <input type="text" name="no_faktur" class="form-control"
               value="{{ old('no_faktur', \App\Models\Gaji::generateNoFaktur()) }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Pegawai</label>
        <select name="id_pegawai" id="pegawai" class="form-select" required>
            <option value="">-- Pilih Pegawai --</option>
            @foreach($pegawai as $p)
                <option value="{{ $p->id }}"
                        data-gaji="{{ $p->gaji_pokok }}"
                        {{ old('id_pegawai') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }} - {{ $p->jabatan->nama_jabatan ?? '-' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Tahun</label>
            <input type="number" name="tahun_gaji" id="tahun_gaji" class="form-control"
                   value="{{ old('tahun_gaji', date('Y')) }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Bulan</label>
            <select name="bulan_gaji" id="bulan_gaji" class="form-select" required>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                    <option value="{{ $b }}" {{ old('bulan_gaji') == $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Tanggal Gaji</label>
            <input type="date" name="tgl_gaji" class="form-control"
                   value="{{ old('tgl_gaji', date('Y-m-d')) }}" required>
        </div>
    </div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Jumlah Hadir</label>
        <input type="number" name="jumlah_hadir" id="jumlah_hadir" class="form-control" readonly>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Total Tunjangan</label>
        <input type="number" name="tunjangan_total" id="tunjangan_total"
               class="form-control" value="{{ old('tunjangan_total', 0) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Total Gaji</label>
        <input type="number" name="total_gaji" id="total_gaji" class="form-control" readonly>
    </div>
</div>


    <button type="submit" class="btn btn-primary">Simpan Gaji</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pegawai   = document.getElementById('pegawai');
    const tahun     = document.getElementById('tahun_gaji');
    const bulan     = document.getElementById('bulan_gaji');
    const jmlHadir  = document.getElementById('jumlah_hadir');
    const tunjTotal = document.getElementById('tunjangan_total');
    const totalGaji = document.getElementById('total_gaji');

    async function hitungGaji() {
        const pegawaiId = pegawai.value;
        const thn       = tahun.value;
        const bln       = bulan.value;

        if (!pegawaiId || !thn || !bln) return;

        const resp = await fetch(`{{ route('tatausaha.gaji.hitung') }}?pegawai=${pegawaiId}&tahun=${thn}&bulan=${bln}`);
        const data = await resp.json();

        jmlHadir.value  = data.jumlah_hadir ?? 0;
        tunjTotal.value = data.tunjangan_total ?? 0;
        totalGaji.value = data.total_gaji ?? 0;
    }

    pegawai.addEventListener('change', hitungGaji);
    tahun.addEventListener('change', hitungGaji);
    bulan.addEventListener('change', hitungGaji);
});
</script>
@endsection
