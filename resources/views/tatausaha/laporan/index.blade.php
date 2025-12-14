@extends('layouts.tatausaha')

@section('title', 'Laporan Bulanan')

@section('content')
    <h3 class="mb-3">Laporan Keuangan Bulanan</h3>

    <form method="POST" action="{{ route('tatausaha.laporan.generate') }}"
          class="card p-4 shadow-sm border-0 mb-4">
        @csrf

        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select" required>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}"
                            {{ (int)$bulan === $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $i, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control" required
                       value="{{ $tahun }}">
            </div>

            <div class="col-md-3">
                <button class="btn btn-success">
                    Generate Laporan
                </button>
            </div>
        </div>
    </form>
@endsection
