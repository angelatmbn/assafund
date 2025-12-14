@extends('layouts.tatausaha')

@section('content')
    <h1>Jurnal Umum</h1>

    <a href="{{ route('tatausaha.jurnal.create') }}" class="btn btn-primary mb-3">
        Tambah Jurnal Manual
    </a>

<table class="table table-bordered">
    <thead>
    <tr>
        <th class="text-center">Tgl</th>
        <th class="text-center">Perkiraan</th>
        <th class="text-center">Ref</th>
        <th class="text-center">Debet</th>
        <th class="text-center">Kredit</th>
    </tr>
    </thead>

<tbody>
@foreach($jurnals as $jurnal)
    @php
        $details = $jurnal->details;
        $first   = true;
    @endphp

    @foreach($details as $d)
        <tr>
            @if($first)
                <td class="text-center" rowspan="{{ $details->count() }}">
                    {{ \Carbon\Carbon::parse($jurnal->tgl)->format('d-m') }}
                </td>
                @php $first = false; @endphp
            @endif

            <td class="text-center">
                @if($d->akun)
                    {{ $d->akun->nama_akun }}
                @else
                    {{ $d->deskripsi }}
                @endif
            </td>
            <td class="text-center">{{ $d->no_akun }}</td>

            <td class="text-end">
                {{ $d->debit ? number_format($d->debit, 0, ',', '.') : '' }}
            </td>
            <td class="text-end">
                {{ $d->credit ? number_format($d->credit, 0, ',', '.') : '' }}
            </td>
        </tr>
    @endforeach
@endforeach
</tbody>
    </table>
@endsection
