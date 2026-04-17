<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    /**
     * Menampilkan daftar portofolio untuk di-review (PBI-03) 
     */
    public function index()
    {
        // Mengambil data portofolio milik vendor yang sedang login [cite: 167, 688]
        $portfolios = Portfolio::where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('dashboard', compact('portfolios'));
    }

    /**
     * Menyimpan bukti visual pengerjaan proyek (PBI-03) [cite: 173, 174]
     */
    public function store(Request $request)
    {
        // Validasi sesuai RNF-03: Batasan file maksimal 5MB [cite: 503]
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'portfolio_file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:5120', 
        ]);

        $file = $request->file('portfolio_file');
        
        // Cek tipe file apakah video atau image untuk database [cite: 173]
        $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
        
        // Simpan file ke storage public (Multimedia Portfolio Repository) [cite: 173, 185]
        $path = $file->store('portfolios', 'public');

        // Simpan data ke database sesuai skema PBI-03 [cite: 688, 722]
        Portfolio::create([
            'user_id' => Auth::id(), // ID Vendor yang login [cite: 692, 793]
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_type' => $type,
        ]);

        return back()->with('success', 'Bukti visual berhasil diunggah!');
    }
}