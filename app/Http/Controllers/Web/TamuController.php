<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index()
    {
        $data = Tamu::orderBy('id', 'DESC')->get();
        return view('tamu.index', compact('data'));
    }

    public function create()
    {
        return view('tamu.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:tamu,email',
            'no_hp' => 'required'
        ]);

        Tamu::create($r->all());

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = Tamu::findOrFail($id);
        return view('tamu.edit', compact('data'));
    }

    public function update(Request $r, $id)
    {
        $data = Tamu::findOrFail($id);

        $data->update($r->all());

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Tamu::destroy($id);
        return back()->with('success', 'Data tamu berhasil dihapus');
    }
}
