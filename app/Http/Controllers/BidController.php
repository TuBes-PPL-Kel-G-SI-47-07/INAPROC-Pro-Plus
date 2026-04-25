<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidController extends Controller
{
        public function calculateScore($bidId)
    {
        $bid = Bid::with('tenderConfig', 'user.surveyReport')->findOrFail($bidId);
        $config = $bid->tenderConfig;

        // 1. Logika Skor Harga (Semakin murah semakin tinggi skornya)
        // Kita ambil penawaran terendah sebagai pembanding
        $minPrice = Bid::where('tender_config_id', $bid->tender_config_id)->min('offered_price');
        $scoreHarga = ($minPrice / $bid->getDecryptedPrice()) * 100;

        // 2. Logika Skor Teknis (Dari skor infrastruktur survey PBI-04)
        $scoreTeknis = $bid->user->surveyReport->infrastructure_score ?? 0;

        // 3. Logika Skor Integritas (Default 100 jika Verified)
        $scoreIntegritas = ($bid->user->status == 'verified') ? 100 : 0;

        // 4. HITUNG BOBOT (PBI-11 Core)
        $finalScore = (
            ($scoreHarga * ($config->weight_harga / 100)) +
            ($scoreTeknis * ($config->weight_teknis / 100)) +
            ($scoreIntegritas * ($config->weight_integritas / 100))
        );

        $bid->update([
            'score_harga' => $scoreHarga,
            'score_teknis' => $scoreTeknis,
            'score_integritas' => $scoreIntegritas,
            'final_score' => $finalScore,
            'status' => 'opened' // Otomatis membuka segel saat dihitung
        ]);

        return redirect()->back()->with('success', 'Skor otomatis berhasil dihitung!');
    }
}
