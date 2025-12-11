<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    /**
     * Tampilkan daftar semua tamu
     */
    public function index()
    {
        $tamus = Tamu::all();
        return view('tamu.index', compact('tamus'));
    }

    /**
     * Tampilkan form untuk membuat tamu baru
     */
    public function create()
    {
        return view('tamu.create');
    }

    /**
     * Simpan data tamu baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tamus,email',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string'
        ]);

        Tamu::create($validated);

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil ditambahkan');
    }

    /**
     * Tampilkan detail tamu berdasarkan ID
     */
    public function show($id)
    {
        $tamu = Tamu::findOrFail($id);
        return view('tamu.show', compact('tamu'));
    }

    /**
     * Tampilkan form untuk edit tamu
     */
    public function edit($id)
    {
        $tamu = Tamu::findOrFail($id);
        return view('tamu.edit', compact('tamu'));
    }

    /**
     * Update data tamu
     */
    public function update(Request $request, $id)
    {
        $tamu = Tamu::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tamus,email,' . $id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string'
        ]);

        $tamu->update($validated);

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diperbarui');
    }

    /**
     * Hapus data tamu
     */
    public function destroy($id)
    {
        $tamu = Tamu::findOrFail($id);
        $tamu->delete();

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil dihapus');
    }
}
