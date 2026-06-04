<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        return response()->json(StrukturOrganisasi::orderBy('urutan')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip'     => 'nullable|string|max:100',
            'foto'    => 'nullable|string',
            'email'   => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:50',
            'urutan'  => 'nullable|integer',
            'aktif'   => 'nullable|boolean',
        ]);
        return response()->json(StrukturOrganisasi::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(StrukturOrganisasi::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $item = StrukturOrganisasi::findOrFail($id);
        $validated = $request->validate([
            'nama'    => 'sometimes|required|string|max:255',
            'jabatan' => 'sometimes|required|string|max:255',
            'nip'     => 'nullable|string|max:100',
            'foto'    => 'nullable|string',
            'email'   => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:50',
            'urutan'  => 'nullable|integer',
            'aktif'   => 'nullable|boolean',
        ]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        StrukturOrganisasi::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
