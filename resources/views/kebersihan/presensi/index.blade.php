@extends('layouts.staff')

@section('title', 'Presensi Kebersihan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Presensi</h3>
        <a href="{{ route('kebersihan.presensi.create') }}" class="btn btn-primary">
            + Tambah Presensi
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Tanggal</th>
            <th>Nama Pegawai</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($presensis as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->tgl_transaksi)->format('d-m-Y') }}</td>
                <td>{{ $p->pegawai->nama ?? '-' }}</td>
                <td>{{ $p->waktu_masuk }}</td>
                <td>{{ $p->waktu_keluar }}</td>
                <td>{{ $p->status_presensi }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
