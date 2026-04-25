<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'tender_config_id' => 'required|exists:tender_configs,id',
        'offered_price' => 'required|numeric|min:0',
    ]);

    \App\Models\Bid::create([
        'tender_config_id' => $request->tender_config_id,
        'user_id' => auth()->id(),
        'price' => $request->offered_price, // Ini otomatis memicu fungsi setPriceAttribute (Enkripsi)
        'hash_key' => bin2hex(random_bytes(16)), // Kunci unik transaksi
        'status' => 'sealed',
    ]);

    return redirect()->back()->with('success', 'Penawaran Anda telah dikunci (Sealed) dalam sistem.');
}
}
