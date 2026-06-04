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

        $project = ProcurementRequest::query()->with('progresses')->findOrFail($request->procurement_request_id);

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

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('bast_file');
        $path = $file->store('bast_documents', 'public');

        // Save BAST
        BastSubmission::query()->updateOrCreate(
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
        ActivityLog::query()->create([
            'user_id' => Auth::id(),
            'action' => 'BAST Submitted',
            'description' => "Vendor mengunggah dokumen BAST untuk Proyek #{$project->id}.",
        ]);

        return redirect()->route('progress.show', $project->id)->with('success', 'Dokumen BAST berhasil diunggah! Menunggu peninjauan Auditor.');
    }

    public function download($id)
    {
        $bast = BastSubmission::query()->findOrFail($id);
        $project = $bast->procurementRequest;

        // Auth check
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('vendor') && $project->vendor_id !== $user->id) {
            abort(403, 'UNAUTHORIZED: Anda tidak memiliki akses ke dokumen ini.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->download($bast->file_path);
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'auditor_notes' => 'nullable|string',
        ]);

        $bast = BastSubmission::query()->findOrFail($id);
        $bast->update([
            'status' => $request->status,
            'auditor_notes' => $request->auditor_notes,
        ]);

        // Audit Trail Log [cite: 599, 600]
        ActivityLog::query()->create([
            'user_id' => Auth::id(),
            'action' => 'BAST Verified',
            'description' => "Auditor memverifikasi dokumen BAST Proyek #{$bast->procurement_request_id} dengan status: {$request->status}",
        ]);

        // Check if both Auditor and Pemohon approved the BAST
        if ($bast->status === 'approved' && $bast->pemohon_status === 'approved') {
            $project = $bast->procurementRequest;
            $project->update(['status' => 'completed']);

            if ($project->tender) {
                $project->tender->update(['status' => 'completed']);
            }

            ActivityLog::query()->create([
                'user_id' => Auth::id(),
                'action' => 'Project Completed',
                'description' => "Proyek #{$project->id} dinyatakan selesai setelah inspeksi final oleh Auditor dan verifikasi akhir oleh Pemohon.",
            ]);
        }

        return redirect()->back()->with('success', 'Status dokumen BAST berhasil diperbarui!');
    }

    public function verifyPemohon(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'pemohon_notes' => 'nullable|string',
        ]);

        $bast = BastSubmission::query()->findOrFail($id);
        $project = $bast->procurementRequest;

        // Authorize: Only the Pemohon of this procurement request can verify
        if ($project->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED: Anda bukan pemohon untuk proyek ini.');
        }

        $bast->update([
            'pemohon_status' => $request->status,
            'pemohon_notes' => $request->pemohon_notes,
        ]);

        // Audit Trail Log for Pemohon verification
        ActivityLog::query()->create([
            'user_id' => Auth::id(),
            'action' => 'BAST Verified by Pemohon',
            'description' => "Pemohon memverifikasi dokumen BAST Proyek #{$bast->procurement_request_id} dengan status: {$request->status}",
        ]);

        // Check if both Auditor and Pemohon approved the BAST
        if ($bast->status === 'approved' && $bast->pemohon_status === 'approved') {
            // Mark project/procurement request as completed
            $project->update(['status' => 'completed']);

            // Mark tender as completed
            if ($project->tender) {
                $project->tender->update(['status' => 'completed']);
            }

            // Log final completion
            ActivityLog::query()->create([
                'user_id' => Auth::id(),
                'action' => 'Project Completed',
                'description' => "Proyek #{$project->id} dinyatakan selesai setelah inspeksi final oleh Auditor dan verifikasi akhir oleh Pemohon.",
            ]);
        }

        return redirect()->back()->with('success', 'Status verifikasi pemohon berhasil diperbarui!');
    }
}
