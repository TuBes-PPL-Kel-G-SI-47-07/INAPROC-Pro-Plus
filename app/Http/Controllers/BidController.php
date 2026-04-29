<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class BidController extends Controller
{
    public function store(Request $request)
    {
        // Logika simpan penawaran (PBI-10)
        $request->validate([
            'tender_id' => 'required|exists:tenders,id',
            'offered_price' => 'required|numeric',
        ]);

        Bid::updateOrCreate(
            [
                'tender_id' => $request->tender_id,
                'user_id' => auth()->id(),
            ],
            [
                'encrypted_price' => Crypt::encryptString($request->offered_price),
                'status' => 'sealed',
            ]
        );

        return redirect()->back()->with('success', 'Penawaran berhasil disimpan dan dienkripsi!');
    }

    public function calculateScore($id)
    {
        $bid = Bid::findOrFail($id);
        $config = \App\Models\TenderConfig::latest()->first();

        if (!$config) {
            return redirect()->back()->withErrors('Konfigurasi bobot tender belum diterbitkan oleh Admin.');
        }

        $decryptedPrice = (float) $bid->getDecryptedPrice();
        
        // Cari harga terendah untuk rasio perhitungan (Semakin murah semakin tinggi skor)
        $allBids = Bid::where('tender_id', $bid->tender_id)->get();
        $minPrice = $allBids->min(function($b) { return (float) $b->getDecryptedPrice(); });

        $scoreHarga = $decryptedPrice > 0 ? ($minPrice / $decryptedPrice) * 100 : 0;

        // Hitung teknis dari hasil survey
        $survey = \App\Models\SurveyReport::where('user_id', $bid->user_id)->first();
        // Asumsi infrastructure_score dan office_condition dalam skala 1-100
        $infra = $survey->infrastructure_score ?? 0;
        $office = $survey->office_condition ?? 0;
        
        // Konversi text office_condition ke angka jika datanya berupa string dari form
        if (is_string($office)) {
            $officeText = strtolower(trim($office));
            if ($officeText == 'layak') $office = 100;
            elseif ($officeText == 'cukup layak') $office = 50;
            else $office = 0;
        }

        $scoreTeknis = ($infra + $office) / 2;

        // Skor integritas default
        $scoreIntegritas = 85; 

        // Kalkulasi nilai akhir sesuai bobot
        $finalScore = ($scoreHarga * $config->weight_harga / 100) + 
                      ($scoreTeknis * $config->weight_teknis / 100) + 
                      ($scoreIntegritas * $config->weight_integritas / 100);

        $bid->update([
            'score_harga' => $scoreHarga,
            'score_teknis' => $scoreTeknis,
            'score_integritas' => $scoreIntegritas,
            'final_score' => $finalScore
        ]);

        return redirect()->back()->with('success', 'Skor evaluasi vendor berhasil dihitung berdasarkan bobot!');
    }

    public function vendorBids()
    {
        $bids = Bid::where('user_id', auth()->id())->with('tender')->latest()->get();
        return view('vendor.my_bids', compact('bids'));
    }

    public function setWinner($id)
    {
        $winnerBid = Bid::findOrFail($id);
        $tender = $winnerBid->tender;

        // 1. Ubah status bid terpilih menjadi 'winner'
        $winnerBid->update(['status' => 'winner']);

        // 2. Ubah status bid lainnya menjadi 'rejected'
        Bid::where('tender_id', $tender->id)
            ->where('id', '!=', $winnerBid->id)
            ->update(['status' => 'rejected']);

        // 3. Update procurement_requests: vendor_id = pemenang, status = completed
        if ($tender->procurement_request_id) {
            $procurement = \App\Models\ProcurementRequest::find($tender->procurement_request_id);
            if ($procurement) {
                $procurement->update([
                    'vendor_id' => $winnerBid->user_id,
                    'status' => 'approved', // Gunakan 'approved' sesuai enum database
                ]);
            }
        }

        // 4. Ubah status tender menjadi closed
        $tender->update(['status' => 'closed']);

        // Catat di Activity Log
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Winner Selected',
            'description' => "Auditor menetapkan Vendor #{$winnerBid->user_id} sebagai pemenang untuk Tender #{$tender->id}",
        ]);

        return redirect()->back()->with('success', 'Pemenang tender berhasil ditetapkan dan SPK siap dicetak!');
    }
}