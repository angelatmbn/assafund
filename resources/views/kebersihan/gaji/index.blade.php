@extends('layouts.staff')

@section('title', 'Penggajian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Penggajian</h3>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No Faktur</th>
            <th>Tanggal</th>
            <th>Nama Pegawai</th>
            <th>Periode</th>
            <th>Total Gaji</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($gajis as $gaji)
            <tr>
                <td>{{ $gaji->no_faktur }}</td>
                <td>{{ $gaji->tgl_gaji }}</td>
                <td>{{ $gaji->pegawai->nama ?? '-' }}</td>
                <td>{{ $gaji->bulan_gaji }} {{ $gaji->tahun_gaji }}</td>
                <td>Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('kebersihan.gaji.slip', $gaji) }}"
                       class="btn btn-sm btn-secondary"
                       target="_blank">
                        Slip Gaji
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
