<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AuditTrailController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->orderBy('id', 'asc')->paginate(50);
        return view('auditor.audit_trail', compact('logs'));
    }

    public function verify()
    {
        $logs = ActivityLog::orderBy('id', 'asc')->get();
        
        $previousHash = null;
        $manipulatedId = null;

        foreach ($logs as $log) {
            // Check previous hash link
            if ($log->previous_hash !== $previousHash) {
                $manipulatedId = $log->id;
                break;
            }

            // Recalculate current hash
            $dataString = $log->user_id . $log->action . $log->description . $log->created_at . $previousHash;
            $recalculatedHash = hash('sha256', $dataString);

            // Check current hash integrity
            if ($log->current_hash !== $recalculatedHash) {
                $manipulatedId = $log->id;
                break;
            }

            // Move forward
            $previousHash = $log->current_hash;
        }

        if ($manipulatedId) {
            return redirect()->route('auditor.audit-trail.index')->with('error', "⚠️ Peringatan: Terdeteksi Manipulasi Data pada Log ID {$manipulatedId}!");
        }

        return redirect()->route('auditor.audit-trail.index')->with('success', 'Verifikasi berhasil. Rantai log (Audit Trail) terbukti utuh dan tidak ada manipulasi data.');
    }
}
