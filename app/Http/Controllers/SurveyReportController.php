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

    /**
     * Menampilkan senarai laporan untuk disahkan oleh Auditor
     */
    public function index()
    {
        // Mengambil semua data survey beserta relasi vendor dan surveyor (jika ada)
        // Kita paparkan data yang terbaru di atas
        $surveys = SurveyReport::with(['user', 'vendor', 'surveyor'])->latest()->get();
        #$surveys = SurveyReport::all();

        return view('auditor.surveys.index', compact('surveys'));
        #return view('auditor.index', compact('surveys'));
    }

    /**
     * Memproses kelulusan atau penolakan laporan
     */
    public function verify(Request $request, SurveyReport $survey)
    {
        // Validasi input dari butang approve/reject
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'auditor_notes' => $request->status === 'rejected' ? 'required|string|min:5' : 'nullable|string', // Wajib diisi jika status ditolak (boleh ditambah logikanya nanti)
        ]);

        // Kemas kini data dalam database
        $survey->update([
            'status' => $request->status,
            'auditor_notes' => $request->auditor_notes,
        ]);

        // Kembali ke halaman sebelumnya dengan mesej berjaya
        return back()->with('success', 'Status laporan Geotagging berhasil diperbarui!');
    }
}