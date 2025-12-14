<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; }
        .box { border: 2px solid #000; padding: 20px; margin: 20px 0; }
        .row { margin: 10px 0; }
        .label { display: inline-block; width: 150px; font-weight: bold; }
        .value { display: inline-block; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f0f0f0; }
    </style>
</head>
<body>
<div class="header">
    <h2>Slip Gaji Pegawai</h2>
</div>

@php
    $pegawai    = $gaji->pegawai;
    $gaji_pokok = (float) ($gaji->gaji_pokok ?? $pegawai->gaji_pokok ?? 0);
    $tunjangan  = (float) ($gaji->tunjangan_total ?? $pegawai->tunjangan ?? 0);
    $total_gaji = (float) ($gaji->total_gaji ?? ($gaji_pokok + $tunjangan));
@endphp

<div class="box">
    <div class="row">
        <span class="label">Nama Pegawai:</span>
        <span class="value">{{ $pegawai->nama ?? '-' }}</span>
    </div>
    <div class="row">
        <span class="label">NIP:</span>
        <span class="value">{{ $pegawai->nip ?? '-' }}</span>
    </div>
    <div class="row">
        <span class="label">Jabatan:</span>
        <span class="value">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</span>
    </div>
</div>

<div class="box">
    <table class="table">
        <thead>
        <tr>
            <th>Keterangan</th>
            <th>Nominal (Rp)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Gaji Pokok</td>
            <td>{{ number_format($gaji_pokok, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunjangan</td>
            <td>{{ number_format($tunjangan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Gaji</th>
            <th>{{ number_format($total_gaji, 0, ',', '.') }}</th>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
