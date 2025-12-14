<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\JurnalDetail;

class JurnalController extends Controller
{
    public function index()
    {
        $jurnals = Jurnal::with('details')
            ->orderByDesc('tgl')
            ->get();

        return view('tatausaha.jurnal.index', compact('jurnals'));
    }

    public function create()
    {
        return view('tatausaha.jurnal.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tgl'          => 'required|date',
            'no_referensi' => 'required|string|max:50',
            'deskripsi'    => 'required|string',
            'detail'       => 'required|array|min:1',
            'detail.*.no_akun'   => 'required|integer',
            'detail.*.deskripsi' => 'required|string',
            'detail.*.debit'     => 'required|numeric',
            'detail.*.credit'    => 'required|numeric',
        ]);

        $jurnal = Jurnal::create([
            'tgl'          => $data['tgl'],
            'no_referensi' => $data['no_referensi'],
            'deskripsi'    => $data['deskripsi'],
        ]);

        foreach ($data['detail'] as $row) {
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'no_akun'   => $row['no_akun'],
                'deskripsi' => $row['deskripsi'],
                'debit'     => $row['debit'],
                'credit'    => $row['credit'],
            ]);
        }

        return redirect()
            ->route('tatausaha.jurnal.index')
            ->with('success', 'Jurnal berhasil disimpan.');
    }
}
