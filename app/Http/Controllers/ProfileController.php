<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // Import Storage
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman Setup Profil Perusahaan (PBI-02/PBI-04)
     */
    public function setup(): View
    {
        return view('profile.setup', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Ambil data yang sudah divalidasi
        $data = $request->validated();

        // 2. Logika khusus untuk Foto Profil (PBI-02)
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada untuk menghemat storage
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            
            // Simpan foto baru ke folder 'profiles' di disk public 
            $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        // 3. Masukkan data ke model (fill) dan simpan
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}