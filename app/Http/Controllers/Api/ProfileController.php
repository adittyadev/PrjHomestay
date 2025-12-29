<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Menampilkan profile
    public function index(Request $request)
    {
        $user = $request->user()->load('tamu');

        // Format foto URL dengan full URL
        $tamu = $user->tamu;
        if ($tamu && $tamu->foto) {
            // Jika foto sudah berupa full URL, biarkan
            if (!filter_var($tamu->foto, FILTER_VALIDATE_URL)) {
                // Jika bukan URL, tambahkan base URL
                $tamu->foto = url($tamu->foto);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'tamu' => $tamu,
            ],
        ]);
    }

    // Update profile
    public function update(Request $request)
    {
        $user = $request->user();
        $tamu = $user->tamu;

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update user email
        $user->email = $request->email;
        $user->name = $request->nama;
        $user->save();

        // Update tamu data
        $tamu->nama = $request->nama;
        $tamu->no_hp = $request->no_hp;
        $tamu->alamat = $request->alamat;

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($tamu->foto) {
                // Extract path from URL if it's a full URL
                $oldPath = str_replace(url('/storage/'), '', $tamu->foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store new foto
            $path = $request->file('foto')->store('photos', 'public');

            // Save as full URL instead of relative path
            $tamu->foto = url(Storage::url($path));
        }

        $tamu->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diupdate',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'tamu' => $tamu,
            ],
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $request->user()->currentAccessToken();
        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
