@extends('layouts.tatausaha')

@section('title', 'Chart of Account')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Chart of Account</h3>
        <a href="{{ route('tatausaha.coa.create') }}" class="btn btn-primary">
            + Tambah Akun
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Header Akun</th>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($coas as $c)
            <tr>
                <td>{{ $c->header_akun }}</td>
                <td>{{ $c->no_akun }}</td>
                <td>{{ $c->nama_akun }}</td>
                <td>
                    <a href="{{ route('tatausaha.coa.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                        Edit
                    </a>
                    <form action="{{ route('tatausaha.coa.destroy', $c) }}" method="POST"
                          class="d-inline" onsubmit="return confirm('Hapus akun ini?')">
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
