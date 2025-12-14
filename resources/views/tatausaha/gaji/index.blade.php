@extends('layouts.tatausaha')

@section('title', 'Penggajian')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Penggajian</h3>
        <a href="{{ route('tatausaha.gaji.create') }}" class="btn btn-primary">
            + Tambah Gaji
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>No Faktur</th>
            <th>Tanggal</th>
            <th>Nama Pegawai</th>
            <th>Tahun</th>
            <th>Bulan</th>
            <th>Jumlah Hadir</th>
            <th>Total Gaji</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($gajis as $g)
            <tr>
                <td>{{ $g->no_faktur }}</td>
                <td>{{ \Carbon\Carbon::parse($g->tgl_gaji)->format('d-m-Y') }}</td>
                <td>{{ $g->pegawai->nama ?? '-' }}</td>
                <td>{{ $g->tahun_gaji }}</td>
                <td>{{ $g->bulan_gaji }}</td>
                <td>{{ $g->jumlah_hadir }}</td>
                <td>{{ 'Rp ' . number_format($g->total_gaji, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('tatausaha.gaji.edit', $g) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('tatausaha.gaji.destroy', $g) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus data gaji ini?')">
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
