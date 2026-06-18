<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementRequest;
use App\Models\Tender;

class AuditorController extends Controller
{
    public function monitoring()
    {
        // Get all procurement requests with their budgets and tenders (including bid count)
        $procurements = ProcurementRequest::with(['budget', 'tender' => function ($query) {
            $query->withCount('bids');
        }])->latest()->get();

        // Calculate metrics
        $totalRunningTenders = Tender::where('status', 'open')->count();
        $totalSurveyNeeded = ProcurementRequest::where('status', 'approved')->whereNull('vendor_id')->count();
        $totalCompletedTenders = Tender::where('status', 'closed')->orWhere('status', 'completed')->count();

        return view('auditor.monitoring', compact(
            'procurements', 
            'totalRunningTenders', 
            'totalSurveyNeeded', 
            'totalCompletedTenders'
        ));
    }
}
