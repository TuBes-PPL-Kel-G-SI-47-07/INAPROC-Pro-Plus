<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Budget;
use App\Models\ProcurementRequest;
use App\Models\Bid;
use App\Models\Tender;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditorAnalyticsController extends Controller
{
    public function index()
    {
        // 1. Core KPIs
        $totalTenders = Tender::query()->count();
        $completedProjects = ProcurementRequest::query()->where('status', 'completed')->count();
        
        // 2. Calculate savings and budget efficiency
        $budgetsData = [];
        $budgets = Budget::query()->get();
        foreach ($budgets as $budget) {
            // Find completed procurement requests for this budget
            $requests = ProcurementRequest::query()
                ->where('budget_id', $budget->id)
                ->where('status', 'completed')
                ->get();
            
            $initialTotal = 0;
            $finalTotal = 0;
            foreach ($requests as $req) {
                $initialTotal += $req->total_price;
                // Find winning bid price
                $winningBid = Bid::query()
                    ->where('tender_id', $req->tender->id ?? 0)
                    ->where('status', 'winner')
                    ->first();
                if ($winningBid) {
                    try {
                        $finalTotal += (float) $winningBid->getDecryptedPrice();
                    } catch (\Exception $e) {
                        $finalTotal += $req->total_price;
                    }
                } else {
                    $finalTotal += $req->total_price; // fallback if no winner record
                }
            }
            
            $budgetsData[] = [
                'name' => $budget->nama_pagu,
                'initial' => $initialTotal,
                'final' => $finalTotal,
                'saving' => $initialTotal - $finalTotal,
            ];
        }

        // Sum overall savings
        $totalSavings = array_sum(array_column($budgetsData, 'saving'));

        // 3. Bid anomaly detection (outliers)
        $detectedAnomalies = [];
        $tendersList = Tender::query()->with('bids.user')->get();
        foreach ($tendersList as $tender) {
            $bids = $tender->bids;
            if ($bids->count() < 2) {
                continue;
            }
            
            // Decrypt prices
            $prices = [];
            foreach ($bids as $bid) {
                try {
                    $price = (float) $bid->getDecryptedPrice();
                    $prices[$bid->id] = $price;
                } catch (\Exception $e) {
                    // skip if error decrypting
                }
            }
            
            if (empty($prices)) {
                continue;
            }
            
            $average = array_sum($prices) / count($prices);
            foreach ($bids as $bid) {
                if (!isset($prices[$bid->id])) {
                    continue;
                }
                $price = $prices[$bid->id];
                $deviation = abs($price - $average) / $average;
                
                if ($deviation > 0.30) {
                    $detectedAnomalies[] = [
                        'tender_title' => $tender->title,
                        'vendor_name' => $bid->user->name ?? 'Unknown',
                        'price' => $price,
                        'average' => $average,
                        'deviation' => $deviation * 100,
                        'type' => $price < $average ? 'Underpricing (Dumping)' : 'Overpricing (Potential Mark-up)',
                        'bid_id' => $bid->id,
                    ];
                }
            }
        }

        // 4. Sebaran Vendor (Geospatial Coordinates)
        /** @var \Illuminate\Database\Eloquent\Builder $vendorQuery */
        $vendorQuery = User::role('vendor');
        $vendors = $vendorQuery->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('surveyReport')
            ->get()
            ->map(function ($vendor) {
                return [
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'phone_number' => $vendor->phone_number,
                    'address' => $vendor->address,
                    'latitude' => (float) $vendor->latitude,
                    'longitude' => (float) $vendor->longitude,
                    'survey_score' => $vendor->surveyReport->infrastructure_score ?? 'Belum disurvey',
                    'office_condition' => $vendor->surveyReport->office_condition ?? 'Belum disurvey',
                ];
            });

        // 5. Activity Logs (Immutable logs)
        $activityLogs = ActivityLog::query()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('auditor.analytics', compact(
            'totalTenders',
            'completedProjects',
            'totalSavings',
            'budgetsData',
            'detectedAnomalies',
            'vendors',
            'activityLogs'
        ));
    }
}
