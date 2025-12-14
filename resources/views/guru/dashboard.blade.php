{{-- resources/views/guru/dashboard.blade.php --}}
@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="mb-3">
        <h3 class="mb-1" style="color:#00933F;">
            Selamat Datang, {{ $userName }}
        </h3>
        <div class="text-muted" style="font-size:13px;">
            Ringkasan data siswa dan presensi hari ini
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase" style="font-size:11px;">Jumlah Siswa</span>
                        <span class="badge rounded-pill text-bg-success">Aktif</span>
                    </div>
                    <h2 class="mb-1">{{ $jumlahSiswa }}</h2>
                    <small class="text-muted">Total siswa terdaftar di sistem</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase" style="font-size:11px;">Presensi Hari Ini</span>
                        <span class="badge rounded-pill text-bg-success">Hari ini</span>
                    </div>
                    <h2 class="mb-1">{{ $presensiHari }}</h2>
                    <small class="text-muted">Jumlah presensi yang sudah tercatat</small>
                </div>
            </div>
        </div>
    </div>
@endsection
