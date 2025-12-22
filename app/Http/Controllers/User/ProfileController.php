<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tamu;

class ProfileController extends Controller
{
    public function index()
    {
        $tamu = Tamu::where('user_id', Auth::id())->firstOrFail();
        return view('user.profile.index', compact('tamu'));
    }

    public function edit()
    {
        $tamu = Tamu::where('user_id', Auth::id())->firstOrFail();
        return view('user.profile.edit', compact('tamu'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'no_hp'  => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $tamu = Auth::user()->tamu;

        $tamu->update([
            'nama'   => $request->nama,
            'email'  => $request->email,
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
