<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('id', 'DESC')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama_kamar' => 'required',
            'kapasitas' => 'required|numeric',
            'harga' => 'required|numeric',
            'status' => 'required',
            'foto' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = null;

        if ($r->hasFile('foto')) {
            $filename = $r->file('foto')->store('rooms', 'public');
        }

        Room::create([
            'nama_kamar' => $r->nama_kamar,
            'kapasitas'   => $r->kapasitas,
            'harga'       => $r->harga,
            'status'      => $r->status,
            'foto'        => $filename
        ]);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $r, $id)
    {
        $room = Room::findOrFail($id);

        $r->validate([
            'nama_kamar' => 'required',
            'kapasitas' => 'required|numeric',
            'harga' => 'required|numeric',
            'status' => 'required',
            'foto' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = $room->foto;

        if ($r->hasFile('foto')) {

            // Hapus foto lama jika ada
            if ($filename && file_exists(storage_path('app/public/' . $filename))) {
                unlink(storage_path('app/public/' . $filename));
            }

            // Upload foto baru
            $filename = $r->file('foto')->store('rooms', 'public');
        }

        $room->update([
            'nama_kamar' => $r->nama_kamar,
            'kapasitas'  => $r->kapasitas,
            'harga'      => $r->harga,
            'status'     => $r->status,
            'foto'       => $filename
        ]);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);

        if ($room->foto && file_exists(storage_path('app/public/' . $room->foto))) {
            unlink(storage_path('app/public/' . $room->foto));
        }

        $room->delete();

        return back()->with('success', 'Kamar berhasil dihapus!');
    }
}
