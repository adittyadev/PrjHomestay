<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    /**
     * Tampilkan semua data tamu
     */
    public function index()
    {
        $tamus = Tamu::all();
        return response()->json([
            'success' => true,
            'message' => 'Data tamu berhasil diambil',
            'data' => $tamus
        ], 200);
    }

    /**
     * Tampilkan detail tamu berdasarkan ID
     */
    public function show($id)
    {
        $tamu = Tamu::find($id);
        
        if (!$tamu) {
            return response()->json([
                'success' => false,
                'message' => 'Data tamu tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail tamu berhasil diambil',
            'data' => $tamu
        ], 200);
    }

    /**
     * Buat data tamu baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tamus,email',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string'
        ]);

        $tamu = Tamu::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data tamu berhasil dibuat',
            'data' => $tamu
        ], 201);
    }

    /**
     * Update data tamu
     */
    public function update(Request $request, $id)
    {
        $tamu = Tamu::find($id);
        
        if (!$tamu) {
            return response()->json([
                'success' => false,
                'message' => 'Data tamu tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tamus,email,' . $id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string'
        ]);

        $tamu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data tamu berhasil diperbarui',
            'data' => $tamu
        ], 200);
    }

    /**
     * Hapus data tamu
     */
    public function destroy($id)
    {
        $tamu = Tamu::find($id);
        
        if (!$tamu) {
            return response()->json([
                'success' => false,
                'message' => 'Data tamu tidak ditemukan'
            ], 404);
        }

        $tamu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data tamu berhasil dihapus'
        ], 200);
    }
}
