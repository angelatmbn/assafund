{{-- resources/views/guru/siswa/index.blade.php --}}
@extends('layouts.guru')

@section('title', 'Siswa')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Siswa</h3>
        <a href="{{ route('guru.siswa.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>JK</th>
        </tr>
        </thead>
        <tbody>
        @foreach($siswa as $s)
            <tr>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->nama_lengkap }}</td>
                <td>{{ $s->kelas }}</td>
                <td>{{ $s->status }}</td>
                <td>{{ $s->jenis_kelamin }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
