<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\BastSubmission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class BastSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'procurement_request_id' => 'required|exists:procurement_requests,id',
            'bast_file' => 'required|file|mimes:pdf,docx,jpg,jpeg,png|max:5120', // Max 5MB [cite: 503, 648]
            'description' => 'nullable|string',
        ]);

        $project = ProcurementRequest::with('progresses')->findOrFail($request->procurement_request_id);

        if ($project->vendor_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED: Anda bukan pelaksana proyek ini.');
        }

        // Check if max approved progress is 100%
        $maxApprovedProgress = $project->progresses()
            ->where('status', 'approved')
            ->max('percentage') ?? 0;

        if ($maxApprovedProgress < 100) {
            return redirect()->back()->withErrors('Gagal: Dokumen BAST hanya dapat diunggah setelah progres pengerjaan yang disetujui mencapai 100%.');
        }

        $file = $request->file('bast_file');
        $path = $file->store('bast_documents', 'public');

        // Save BAST
        BastSubmission::updateOrCreate(
            ['procurement_request_id' => $project->id],
            [
                'vendor_id' => Auth::id(),
                'file_path' => $path,
                'description' => $request->description,
                'status' => 'pending',
                'auditor_notes' => null,
            ]
        );

        // Audit Trail Log [cite: 599, 600]
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'BAST Submitted',
            'description' => "Vendor mengunggah dokumen BAST untuk Proyek #{$project->id}.",
        ]);

        return redirect()->route('progress.show', $project->id)->with('success', 'Dokumen BAST berhasil diunggah! Menunggu peninjauan Auditor.');
    }

    public function download($id)
    {
        $bast = BastSubmission::findOrFail($id);
        $project = $bast->procurementRequest;

        // Auth check
        $user = Auth::user();
        if ($user->hasRole('vendor') && $project->vendor_id !== $user->id) {
            abort(403, 'UNAUTHORIZED: Anda tidak memiliki akses ke dokumen ini.');
        }

        return Storage::disk('public')->download($bast->file_path);
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'auditor_notes' => 'nullable|string',
        ]);

        $bast = BastSubmission::findOrFail($id);
        $bast->update([
            'status' => $request->status,
            'auditor_notes' => $request->auditor_notes,
        ]);

        // Audit Trail Log [cite: 599, 600]
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'BAST Verified',
            'description' => "Auditor memverifikasi dokumen BAST Proyek #{$bast->procurement_request_id} dengan status: {$request->status}",
        ]);

        return redirect()->back()->with('success', 'Status dokumen BAST berhasil diperbarui!');
    }
}
