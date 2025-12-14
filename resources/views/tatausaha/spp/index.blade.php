@extends('layouts.tatausaha')

@section('title', 'Pembayaran SPP')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Pembayaran SPP</h3>
        <a href="{{ route('tatausaha.spp.create') }}" class="btn btn-primary">
            + Tambah Pembayaran
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Tanggal</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Bulan</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($spps as $spp)
            <tr>
                <td>{{ \Carbon\Carbon::parse($spp->tanggal_bayar)->format('d-m-Y') }}</td>
                <td>{{ $spp->siswa->nama_lengkap ?? '-' }}</td>
                <td>{{ $spp->siswa->kelas ?? '-' }}</td>
                <td>{{ $spp->bulan }} {{ $spp->tahun }}</td>
                <td>{{ 'Rp ' . number_format($spp->biaya_pokok, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('tatausaha.spp.edit', $spp) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('tatausaha.spp.destroy', $spp) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus pembayaran ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
