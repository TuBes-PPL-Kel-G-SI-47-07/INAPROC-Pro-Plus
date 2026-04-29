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
            'judul_tender' => 'required|exists:procurement_requests,id',
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

        $procurement = \App\Models\ProcurementRequest::findOrFail($request->judul_tender);
        $title = 'Tender: ' . $procurement->item_name;

        $tenderConfigData = $request->all();
        $tenderConfigData['judul_tender'] = $title;
        $tenderConfig = TenderConfig::create($tenderConfigData);

        if ($request->hasFile('tor_file')) {
            $path = $request->file('tor_file')->store('procurement_docs', 'public');
            
            ProcurementFile::create([
                'tender_config_id' => $tenderConfig->id,
                'file_name' => $request->file('tor_file')->getClientOriginalName(),
                'file_path' => $path,
            ]);
        }

        // PBI: Create Tender ketika admin menerbitkan konfigurasi tender
        if (!$procurement->tender) {
            \App\Models\Tender::create([
                'procurement_request_id' => $procurement->id,
                'title' => $title,
                'description' => $procurement->description ?? 'Pengadaan ' . $procurement->item_name,
                'status' => 'open',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
            ]);
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Tender Configuration Published',
            'description' => "Tender dipublikasikan dengan Bobot - Harga: {$request->weight_harga}%, Teknis: {$request->weight_teknis}%, Integritas: {$request->weight_integritas}% (Tender: {$title})",
        ]);

        return redirect()->back()->with('success', 'Paket Tender & Dokumen TOR Berhasil Diterbitkan dan Vendor kini dapat melakukan penawaran.');
    }
}