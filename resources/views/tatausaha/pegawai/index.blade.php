@extends('layouts.tatausaha')

@section('title', 'Data Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Pegawai</h3>
    <a href="{{ route('tatausaha.pegawai.create') }}" class="btn btn-primary">
        + Tambah Pegawai
    </a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-light">
    <tr>
        <th>NIP</th>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>Gaji Pokok</th>
        <th>Gender</th>
        <th>Aksi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($pegawai as $p)
        <tr>
            <td>{{ $p->nip }}</td>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->jabatan->nama_jabatan ?? '-' }}</td>
            <td>{{ 'Rp ' . number_format($p->gaji_pokok, 0, ',', '.') }}</td>
            <td>{{ $p->gender }}</td>
            <td>
                <a href="{{ route('tatausaha.pegawai.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('tatausaha.pegawai.destroy', $p) }}" method="POST" class="d-inline">
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
