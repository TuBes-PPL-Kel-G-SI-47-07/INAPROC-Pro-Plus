<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenderConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProcurementFile;

class TenderConfigController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul_tender' => 'required|string',
            'weight_harga' => 'required|integer|min:0|max:100',
            'weight_teknis' => 'required|integer|min:0|max:100',
            'weight_integritas' => 'required|integer|min:0|max:100',
            'tor_file' => 'required|mimes:pdf,docx|max:5120'
        ]);

        // Validasi total bobot harus 100%
        $total = $request->weight_harga + $request->weight_teknis + $request->weight_integritas;

        if ($total !== 100) {
            return redirect()->back()->withErrors([
                'total_weight' => "Total bobot harus 100%! Saat ini totalnya: $total%"
            ])->withInput();
        }

        $tender = TenderConfig::create($request->all());

        if ($request->hasFile('tor_file')) {
            $path = $request->file('tor_file')->store('procurement_docs', 'public');
            
            ProcurementFile::create([
                'tender_config_id' => $tender->id,
                'file_name' => $request->file('tor_file')->getClientOriginalName(),
                'file_path' => $path,
            ]);
        }

    return redirect()->back()->with('success', 'Paket Tender & Dokumen TOR Berhasil Diterbitkan.');
}
}