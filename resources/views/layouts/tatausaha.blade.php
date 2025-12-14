{{-- resources/views/layouts/tatausaha.blade.php --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title') - MI Assalafiyah Batujajar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;      /* sama seperti staff/guru: konten dari atas */
            justify-content: center;
            padding: 32px 24px;
            background: radial-gradient(circle at top left, #e6f8ec 0, #ffffff 40%, #e6f8ec 100%);
        }

        .shell {
            width: 100%;
            max-width: 1500px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(0,0,0,0.06);
            padding: 28px 32px;
            display: grid;
            grid-template-columns: 230px 1fr;
            gap: 24px;
            min-height: calc(100vh - 64px);
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: #00933F;
            margin-bottom: 4px;
        }

        .sidebar-sub {
            font-size: 13px;
            color: #2e7d32;
            margin-bottom: 18px;
        }

        .nav-pills .nav-link {
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 14px;
            color: #374151;
        }

        .nav-pills .nav-link.active {
            background-color: #00933F;
            color: #ffffff;
        }

        .btn-primary {
            background-color: #00933F;
            border-color: #00933F;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #007a34;
            border-color: #007a34;
        }
        .nav-pills .nav-link {
    border-radius: 999px;
    padding: 8px 14px;
    font-size: 14px;
    color: #374151;
    transition: background-color 0.15s ease, color 0.15s ease;
}

.nav-pills .nav-link:hover {
    background-color: #c7f0d6; /* hijau muda */
    color: #006b2c;           /* teks hijau lebih gelap */
}

.nav-pills .nav-link.active {
    background-color: #00933F;
    color: #ffffff;
}

        @media (max-width: 960px) {
            body { padding: 12px; }
            .shell {
                grid-template-columns: 1fr;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>
<div class="shell">
    {{-- Sidebar --}}
    <aside>
        <div class="d-flex flex-column align-items-center mb-3">
            <img src="{{ asset('images/logoassafund.png') }}" alt="Assafund"
                 style="height: 100px; width: auto; margin-bottom: 8px;">
        </div>

        <div class="sidebar-title">Halaman Tata Usaha</div>
        <div class="sidebar-sub">Panel Keuangan & Administrasi</div>

        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('tatausaha.dashboard') }}"
                   class="nav-link {{ request()->routeIs('tatausaha.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tatausaha.gaji.index') }}"
                   class="nav-link {{ request()->routeIs('tatausaha.gaji.*') ? 'active' : '' }}">
                    Penggajian
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tatausaha.spp.index') }}"
                   class="nav-link {{ request()->routeIs('tatausaha.spp.*') ? 'active' : '' }}">
                    Pembayaran SPP
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tatausaha.pendaftaran.index') }}"
                   class="nav-link {{ request()->routeIs('tatausaha.pendaftaran.*') ? 'active' : '' }}">
                    Pendaftaran
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tatausaha.pegawai.index') }}"
                   class="nav-link {{ request()->routeIs('tatausaha.pegawai.*') ? 'active' : '' }}">
                    Pegawai
                </a>
            <li class="nav-item">
                <a href="{{ route('tatausaha.presensi.index') }}"
                class="nav-link {{ request()->routeIs('tatausaha.presensi.*') ? 'active' : '' }}">
                    Presensi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tatausaha.siswa.index') }}"
                   class="nav-link {{ request()->routeIs('tatausaha.siswa.*') ? 'active' : '' }}">
                    Siswa
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tatausaha.coa.index') }}"
                   class="nav-link {{ request()->routeIs('tatausaha.coa.*') ? 'active' : '' }}">
                    Chart of Account
                </a>
            </li>
            <!-- <li class="nav-item">
                <a href="{{ route('tatausaha.jurnal.index') }}"
                class="nav-link {{ request()->routeIs('tatausaha.jurnal.*') ? 'active' : '' }}">
                    Jurnal
                </a>
            </li> -->
            <li class="nav-item">
    <a href="{{ route('tatausaha.laporan.index') }}"
       class="nav-link {{ request()->routeIs('tatausaha.laporan.*') ? 'active' : '' }}">
        Laporan Bulanan
    </a>
</li>

        </ul>
    </aside>

    {{-- Konten --}}
    <main>
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
