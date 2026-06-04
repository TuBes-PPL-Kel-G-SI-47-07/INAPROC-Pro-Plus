<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\ProjectProgress;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProjectProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('vendor')) {
            // Projects won by this vendor
            $projects = ProcurementRequest::where('vendor_id', $user->id)
                ->where('status', 'approved')
                ->latest()
                ->get();
        } else {
            // Admin, Auditor, or Pemohon see all projects with a vendor assigned
            $projects = ProcurementRequest::whereNotNull('vendor_id')
                ->where('status', 'approved')
                ->latest()
                ->get();
        }

        return view('progress.index', compact('projects'));
    }

    public function show($id)
    {
        $project = ProcurementRequest::with(['user', 'vendor', 'progresses.vendor'])->findOrFail($id);
        
        // Authorization check
        $user = Auth::user();
        if ($user->hasRole('vendor') && $project->vendor_id !== $user->id) {
            abort(403, 'UNAUTHORIZED: Anda bukan pemenang tender/proyek ini.');
        }

        return view('progress.show', compact('project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'procurement_request_id' => 'required|exists:procurement_requests,id',
            'percentage' => 'required|integer|min:0|max:100',
            'description' => 'required|string',
            'progress_photo' => 'required|image|max:5120', // Max 5MB [cite: 503, 648]
        ]);

        $project = ProcurementRequest::findOrFail($request->procurement_request_id);

        if ($project->vendor_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED: Anda bukan pelaksana proyek ini.');
        }

        $file = $request->file('progress_photo');
        $tempPath = $file->getRealPath();

        // Extract metadata using PHP EXIF [cite: 567, 694]
        $gpsData = $this->getGpsFromExif($tempPath);

        // Store file [cite: 591, 706]
        $path = $file->store('progress_photos', 'public');

        $status = 'pending';
        $latitude = null;
        $longitude = null;
        $takenAt = null;

        if ($gpsData) {
            $latitude = $gpsData['latitude'];
            $longitude = $gpsData['longitude'];
            $takenAt = $gpsData['taken_at'] ? \Carbon\Carbon::parse($gpsData['taken_at']) : null;
        } else {
            // Flag as anomaly if EXIF metadata is missing (as required by security profiling) [cite: 748, 749]
            $status = 'anomaly';
        }

        // Simpan ke database
        $progress = ProjectProgress::create([
            'procurement_request_id' => $project->id,
            'vendor_id' => Auth::id(),
            'percentage' => $request->percentage,
            'description' => $request->description,
            'photo_path' => $path,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'taken_at' => $takenAt,
            'status' => $status,
        ]);

        // Audit Trail Log [cite: 599, 600, 802]
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Progress Updated',
            'description' => "Vendor mengunggah progres {$request->percentage}% untuk Proyek #{$project->id}. Status: {$status}",
        ]);

        $msg = $status === 'anomaly' 
            ? 'Progres berhasil diunggah! Sistem mendeteksi ANOMALI: Foto tidak memiliki metadata koordinat GPS asli.' 
            : 'Progres berhasil diunggah dan sedang ditinjau!';

        return redirect()->route('progress.show', $project->id)->with('success', $msg);
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,anomaly',
            'auditor_notes' => 'nullable|string',
        ]);

        $progress = ProjectProgress::findOrFail($id);
        $progress->update([
            'status' => $request->status,
            'auditor_notes' => $request->auditor_notes,
        ]);

        // Audit Trail Log [cite: 599, 600]
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Progress Verified',
            'description' => "Auditor memverifikasi progres {$progress->percentage}% Proyek #{$progress->procurement_request_id} dengan status: {$request->status}",
        ]);

        return redirect()->back()->with('success', 'Status progres visual berhasil diperbarui!');
    }

    private function getGpsFromExif($filePath)
    {
        if (!function_exists('exif_read_data')) {
            return null;
        }

        try {
            $exif = @exif_read_data($filePath);
            if (!$exif || !isset($exif['GPSLatitude']) || !isset($exif['GPSLongitude'])) {
                return null;
            }

            $lat = $this->getGpsValue($exif['GPSLatitude'], $exif['GPSLatitudeRef'] ?? 'N');
            $lng = $this->getGpsValue($exif['GPSLongitude'], $exif['GPSLongitudeRef'] ?? 'E');

            return [
                'latitude' => $lat,
                'longitude' => $lng,
                'taken_at' => isset($exif['DateTimeOriginal']) ? $exif['DateTimeOriginal'] : (isset($exif['DateTime']) ? $exif['DateTime'] : null),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getGpsValue($coordinate, $ref)
    {
        $degrees = count($coordinate) > 0 ? $this->gpsCoordinateToFloat($coordinate[0]) : 0;
        $minutes = count($coordinate) > 1 ? $this->gpsCoordinateToFloat($coordinate[1]) : 0;
        $seconds = count($coordinate) > 2 ? $this->gpsCoordinateToFloat($coordinate[2]) : 0;

        $flip = ($ref == 'W' || $ref == 'S') ? -1 : 1;

        return $flip * ($degrees + ($minutes / 60) + ($seconds / 3600));
    }

    private function gpsCoordinateToFloat($coord)
    {
        $parts = explode('/', $coord);
        if (count($parts) <= 0) {
            return 0;
        }
        if (count($parts) == 1) {
            return (float)$parts[0];
        }
        $den = (float)$parts[1];
        if ($den == 0) {
            return (float)$parts[0];
        }
        return (float)$parts[0] / $den;
    }
}
