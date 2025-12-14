@extends('layouts.tatausaha')

@section('content')
    <h1>Tambah Jurnal Manual</h1>

    <form action="{{ route('tatausaha.jurnal.store') }}" method="post">
        @csrf

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tgl" class="form-control" value="{{ old('tgl', date('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label>No Referensi</label>
            <input type="text" name="no_referensi" class="form-control" value="{{ old('no_referensi') }}">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}">
        </div>

        <h5>Detail</h5>

        <div class="mb-3">
            <label>No Akun (baris 1)</label>
            <input type="number" name="detail[0][no_akun]" class="form-control">
        </div>
        <div class="mb-3">
            <label>Deskripsi (baris 1)</label>
            <input type="text" name="detail[0][deskripsi]" class="form-control">
        </div>
        <div class="mb-3">
            <label>Debit (baris 1)</label>
            <input type="number" step="0.01" name="detail[0][debit]" class="form-control">
        </div>
        <div class="mb-3">
            <label>Kredit (baris 1)</label>
            <input type="number" step="0.01" name="detail[0][credit]" class="form-control">
        </div>

        <div class="mb-3">
            <label>No Akun (baris 2)</label>
            <input type="number" name="detail[1][no_akun]" class="form-control">
        </div>
        <div class="mb-3">
            <label>Deskripsi (baris 2)</label>
            <input type="text" name="detail[1][deskripsi]" class="form-control">
        </div>
        <div class="mb-3">
            <label>Debit (baris 2)</label>
            <input type="number" step="0.01" name="detail[1][debit]" class="form-control">
        </div>
        <div class="mb-3">
            <label>Kredit (baris 2)</label>
            <input type="number" step="0.01" name="detail[1][credit]" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection
