<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\tamu;
use Illuminate\Http\Request;

class tamuController extends Controller
{
    public function index()
    {
        $tamus = tamu::all();
        return view('admin.tamu.index', compact('tamus'));
    }

    public function create()
    {
        return view('admin.tamu.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama' => 'required',
            'email' => 'required|unique:tamus',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        tamu::create($r->all());

        return redirect()->route('tamu.index')->with('success', 'Tamu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tamu = tamu::findOrFail($id);
        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(Request $r, $id)
    {
        $tamu = tamu::findOrFail($id);

        $r->validate([
            'nama' => 'required',
            'email' => 'required|unique:tamus,email,' . $tamu->id,
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $tamu->update($r->all());

        return redirect()->route('tamu.index')->with('success', 'Tamu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tamu = tamu::findOrFail($id);
        $tamu->delete();

        return back()->with('success', 'Tamu berhasil dihapus!');
    }
}
