<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenderConfig;
use Illuminate\Http\Request;

class TenderConfigController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul_tender' => 'required|string',
            'weight_harga' => 'required|integer|min:0|max:100',
            'weight_teknis' => 'required|integer|min:0|max:100',
            'weight_integritas' => 'required|integer|min:0|max:100',
        ]);

        // Validasi total bobot harus 100%
        $total = $request->weight_harga + $request->weight_teknis + $request->weight_integritas;

        if ($total !== 100) {
            return redirect()->back()->withErrors([
                'total_weight' => "Total bobot harus 100%! Saat ini totalnya: $total%"
            ])->withInput();
        }

        TenderConfig::create($request->all());

        return redirect()->back()->with('success', 'PBI-08: Konfigurasi Bobot Tender Berhasil Disimpan.');
    }
}