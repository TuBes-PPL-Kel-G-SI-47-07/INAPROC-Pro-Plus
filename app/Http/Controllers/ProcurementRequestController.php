<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcurementRequestController extends Controller
{
    /**
     * Menyimpan pengajuan pengadaan baru (PBI-06)
     */
    public function store(Request $request)
{
    // 1. Validasi input dasar
    $request->validate([
        'budget_id' => 'required|exists:budgets,id',
        'item_name' => 'required|string',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
    ]);

    $total_pengajuan = $request->quantity * $request->price;

    // 2. Ambil data Pagu yang dipilih
    $pagu = \App\Models\Budget::findOrFail($request->budget_id);

    // 3. LOGIKA PBI-07: Automated Budget Check
    if ($total_pengajuan > $pagu->sisa_pagu) {
        // Jika lebih besar, kirim error (Gagal Simpan)
        return redirect()->back()->withErrors([
            'budget_error' => "Maaf, sisa Pagu ({$pagu->nama_pagu}) tidak mencukupi. Sisa: Rp " . number_format($pagu->sisa_pagu)
        ])->withInput();
    }

    // 4. Jika lolos cek, baru simpan ke Database
    \App\Models\ProcurementRequest::create([
        'user_id' => auth()->id(),
        'budget_id' => $request->budget_id,
        'item_name' => $request->item_name,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'total_price' => $total_pengajuan,
        'status' => 'pending',
    ]);

    return redirect()->back()->with('success', 'Pengadaan berhasil diajukan! Budget tersedia.');
}
}