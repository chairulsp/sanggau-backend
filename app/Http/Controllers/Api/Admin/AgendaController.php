<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        return response()->json(Agenda::orderBy('tanggal_mulai')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date',
            'lokasi'         => 'nullable|string|max:255',
            'aktif'          => 'nullable|boolean',
        ]);

        return response()->json(Agenda::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(Agenda::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);
        $validated = $request->validate([
            'judul'          => 'sometimes|required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date',
            'lokasi'         => 'nullable|string|max:255',
            'aktif'          => 'nullable|boolean',
        ]);

        $agenda->update($validated);
        return response()->json($agenda);
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
