@extends('layouts.tatausaha')

@section('title', 'Pendaftaran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Pendaftaran</h3>
        <a href="{{ route('tatausaha.pendaftaran.create') }}" class="btn btn-success">
            + Tambah Pendaftaran
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
            <th>Komponen Biaya</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pendaftarans as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $p->siswaRef->nama_lengkap ?? $p->siswa }}</td>
                <td>{{ $p->siswaRef->kelas ?? $p->Kelas }}</td>
                <td>{{ $p->komponenRef->nama_komponen ?? $p->komponen_biaya }}</td>
                <td>{{ 'Rp ' . number_format($p->nominal, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('tatausaha.pendaftaran.edit', $p) }}"
                       class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('tatausaha.pendaftaran.destroy', $p) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus data pendaftaran ini?')">
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
