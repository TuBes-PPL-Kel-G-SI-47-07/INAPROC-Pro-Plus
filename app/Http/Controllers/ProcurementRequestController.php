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
        // 1. Validasi Input: Memastikan data tidak kosong dan sesuai format
        $request->validate([
            'item_name'       => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
            'price'           => 'required|numeric|min:0',
            'description'     => 'nullable|string',
        ]);

        // 2. Kalkulasi Total Harga (Logic PBI-06)
        // Kita hitung di backend agar user tidak bisa memanipulasi total harga dari frontend
        $total_price = $request->quantity * $request->price;

        // 3. Eksekusi Simpan ke Database
        ProcurementRequest::create([
            'user_id'     => Auth::id(), // Mengambil ID user yang sedang login
            'item_name'   => $request->item_name,
            'quantity'    => $request->quantity,
            'price'       => $request->price,
            'total_price' => $total_price,
            'description' => $request->description,
            'status'      => 'pending', // Status awal pengajuan
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pengajuan pengadaan berhasil dikirim! Menunggu verifikasi budget.');
    }
}