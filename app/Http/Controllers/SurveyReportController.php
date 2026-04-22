<?php

namespace App\Http\Controllers;

use App\Models\SurveyReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyReportController extends Controller
{
    /**
     * Menyimpan laporan survey lapangan dan otomatisasi verifikasi (PBI-04)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'office_condition' => 'required|string',
            'infrastructure_score' => 'required|integer|min:0|max:100',
            'survey_photo' => 'required|image|max:2048', 
        ]);

        // Simpan Foto Survey ke storage public
        $path = $request->file('survey_photo')->store('survey_evidence', 'public');

        // Simpan data laporan
        SurveyReport::create([
            'user_id' => $request->user_id,
            'surveyor_id' => Auth::id(),
            'office_condition' => $request->office_condition,
            'infrastructure_score' => $request->infrastructure_score,
            'notes' => $request->notes,
            'survey_photo' => $path,
        ]);

        // Logika Verifikasi Otomatis (Grade A Automation)
        $status = ($request->infrastructure_score >= 70) ? 'verified' : 'rejected';
        User::where('id', $request->user_id)->update(['status' => $status]);

        return redirect()->back()->with('success', 'Laporan survey berhasil disimpan. Status vendor otomatis diperbarui menjadi ' . strtoupper($status) . '!');
    }
}