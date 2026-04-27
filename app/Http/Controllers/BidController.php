<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request)
    {
        // Logika simpan penawaran (PBI-10)
        $request->validate([
            'tender_config_id' => 'required',
            'offered_price' => 'required|numeric',
        ]);

        Bid::create([
            'tender_config_id' => $request->tender_config_id,
            'user_id' => auth()->id(),
            'price' => $request->offered_price, // Trigger encryption di Model
            'status' => 'sealed',
        ]);

        return redirect()->back()->with('success', 'Penawaran berhasil dikunci dan dikirim!');
    }

    public function calculateScore($id)
    {
        // Logika hitung skor otomatis akan kita detailkan nanti
        return redirect()->back()->with('success', 'Skor berhasil dihitung!');
    }
}