<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\ProcurementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProcurementRequestController extends Controller
{
    /**
     * Menampilkan daftar pengajuan pengadaan.
     */
    public function index()
    {
        if (Auth::user()->hasRole('auditor') || Auth::user()->hasRole('admin')) {
            $requests = ProcurementRequest::with(['user', 'budget'])->latest()->get();
        } else {
            $requests = ProcurementRequest::where('user_id', Auth::id())->with('budget')->latest()->get();
        }

        return view('procurement.index', compact('requests'));
    }

    public function create()
    {
        $budgets = Budget::all();
        return view('procurement.create', compact('budgets'));
    }

    /**
     * PBI-07: Store dengan kolom total_price
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'item_name' => 'required|string|max:255',
            'quantity'  => 'required|integer|min:1',
            'price'     => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $total_pengajuan = $validated['quantity'] * $validated['price'];
        $budget = Budget::findOrFail($validated['budget_id']);

        // Automated Pagu Check
        if ($total_pengajuan > $budget->sisa_pagu) {
            return back()->withInput()->with('error', "Gagal! Anggaran tidak mencukupi. Sisa Pagu: Rp " . number_format($budget->sisa_pagu, 0, ',', '.'));
        }

        DB::beginTransaction();
        try {
            $procurement = new ProcurementRequest();
            $procurement->user_id = Auth::id();
            $procurement->budget_id = $validated['budget_id'];
            $procurement->item_name = $validated['item_name'];
            $procurement->quantity = $validated['quantity'];
            $procurement->price = $validated['price'];
            $procurement->total_price = $total_pengajuan; // POIN 2: Sudah ganti ke total_price
            $procurement->description = $request->description;
            $procurement->status = 'pending';
            $procurement->save();

            // Potong Saldo
            $budget->decrement('sisa_pagu', $total_pengajuan);

            DB::commit();
            return redirect()->route('procurement.index')->with('success', 'Pengajuan berhasil dan Pagu dipotong otomatis.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Sistem error: ' . $e->getMessage());
        }
    }

    /**
     * POIN 3: Logika Verify & Refund (Ditaruh di sini agar Auditor bisa mengubah status)
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $procurement = ProcurementRequest::findOrFail($id);
        $budget = Budget::find($procurement->budget_id);

        DB::beginTransaction();
        try {
            // Jika status lama bukan rejected, dan status baru adalah rejected, maka REFUND
            if ($procurement->status !== 'rejected' && $request->status === 'rejected') {
                $budget->increment('sisa_pagu', $procurement->total_price);
            } 
            // Jika status lama rejected tapi mau di-approve lagi (misal salah klik), maka POTONG LAGI
            elseif ($procurement->status === 'rejected' && $request->status === 'approved') {
                if ($procurement->total_price > $budget->sisa_pagu) {
                    return back()->with('error', 'Gagal approve! Pagu sudah tidak cukup untuk restore pengajuan ini.');
                }
                $budget->decrement('sisa_pagu', $procurement->total_price);
            }

            $procurement->status = $request->status;
            $procurement->save();

            DB::commit();
            return back()->with('success', 'Status pengadaan diperbarui dan Pagu disesuaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses verifikasi.');
        }
    }

    public function show(ProcurementRequest $procurementRequest)
    {
        return view('procurement.show', ['request' => $procurementRequest->load(['user', 'budget'])]);
    }
}