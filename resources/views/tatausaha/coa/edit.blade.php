@extends('layouts.tatausaha')

@section('title', 'Edit Akun CoA')

@section('content')
    <h3 class="mb-3">Edit Akun Chart of Account</h3>

    <form method="POST" action="{{ route('tatausaha.coa.update', $coa) }}" class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

<div class="mb-3">
    <label class="form-label">Header Akun</label>
    <input type="number" name="header_akun" class="form-control"
           value="{{ old('header_akun', $coa->header_akun) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">No Akun</label>
    <input type="text" name="no_akun" class="form-control"
           value="{{ old('no_akun', $coa->no_akun) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Nama Akun</label>
    <input type="text" name="nama_akun" class="form-control"
           value="{{ old('nama_akun', $coa->nama_akun) }}" required>
</div>

        <button type="submit" class="btn btn-primary">Update Akun</button>
    </form>
@endsection
