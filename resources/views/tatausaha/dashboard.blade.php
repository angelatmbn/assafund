@extends('layouts.tatausaha')

@section('title', 'Dashboard Tata Usaha')

@section('content')
    <div class="mb-3">
        <h3 class="mb-1" style="color:#00933F;">
            Selamat Datang, {{ $userName }}
        </h3>
        <div class="text-muted" style="font-size:13px;">
            Ringkasan administrasi & keuangan hari ini
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Pegawai</div>
                    <h2 class="mb-1">{{ $totalPegawai }}</h2>
                    <small class="text-muted">Total pegawai terdaftar</small>
                    <div class="mt-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Siswa</div>
                    <h2 class="mb-1">{{ $totalSiswa }}</h2>
                    <small class="text-muted">Total siswa terdaftar</small>
                    <div class="mt-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Penggajian</div>
                    <h2 class="mb-1">Rp {{ number_format($totalNominalGaji, 0, ',', '.') }}</h2>
                    <small class="text-muted">Total gaji dibayarkan</small>
                    <div class="mt-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">SPP</div>
                    <h2 class="mb-1">Rp {{ number_format($totalNominalSPP, 0, ',', '.') }}</h2>
                    <small class="text-muted">Total pembayaran SPP</small>
                    <div class="mt-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase mb-1" style="font-size:11px;">Presensi</div>
                    <h2 class="mb-1">{{ $jumlahPresensi }}</h2>
                    <small class="text-muted">Jumlah Presensi</small>
                    <div class="mt-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2" style="font-size:11px;">
                    Tren Gaji & SPP per Bulan
                </h6>
                <canvas id="chart-gaji-spp" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2" style="font-size:11px;">
                    Komposisi Pegawai & Siswa
                </h6>
                <canvas id="chart-komposisi" height="180"></canvas>
            </div>
        </div>
    </div>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // data dari PHP -> JS
    const gajiData = @json($gajiPerBulan);
    const sppData  = @json($sppPerBulan);

    const labels = gajiData.map(row => row.bulan);

    const mapByBulan = (rows) => {
        const map = {};
        rows.forEach(r => map[r.bulan] = r.total);
        return labels.map(b => map[b] ?? 0);
    };

    const gajiTotals = mapByBulan(gajiData);
    const sppTotals  = mapByBulan(sppData);

    // line/area chart gaji & SPP
    const ctx1 = document.getElementById('chart-gaji-spp').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Gaji',
                    data: gajiTotals,
                    borderColor: '#00933F',
                    backgroundColor: 'rgba(0,147,63,0.15)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'SPP',
                    data: sppTotals,
                    borderColor: '#FF9800',
                    backgroundColor: 'rgba(255,152,0,0.15)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            plugins: { legend: { display: true } },
            scales: {
                y: {
                    ticks: {
                        callback: v => 'Rp ' + v.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // bar chart komposisi pegawai vs siswa
    const ctx2 = document.getElementById('chart-komposisi').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Pegawai', 'Siswa'],
            datasets: [{
                data: [{{ $totalPegawai }}, {{ $totalSiswa }}],
                backgroundColor: ['#00933F', '#2196F3']
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, precision: 0 } }
        }
    });
});
</script>

@endsection
