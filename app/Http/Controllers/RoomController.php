<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kamar' => 'required|string',
            'kapasitas'  => 'required|integer',
            'harga'      => 'required|integer',
            'status'     => 'required|in:available,booked',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/rooms', $filename);
        }

        $room = Room::create([
            'nama_kamar' => $request->nama_kamar,
            'kapasitas'  => $request->kapasitas,
            'harga'      => $request->harga,
            'status'     => $request->status,
            'foto'       => $filename
        ]);

        return response()->json($room, 201);
    }




    public function show($id)
    {
        return Room::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $room->update($request->all());
        return response()->json($room);
    }

    public function destroy($id)
    {
        Room::destroy($id);
        return response()->json(['message' => 'Room deleted']);
    }
}
