<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ppid;
use Illuminate\Http\Request;

class PpidController extends Controller
{
    public function index()
    {
        return response()->json(Ppid::orderBy('urutan')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'file_url' => 'nullable|string',
            'deskripsi'=> 'nullable|string',
            'tahun'    => 'nullable|string|max:10',
            'urutan'   => 'nullable|integer',
            'aktif'    => 'nullable|boolean',
        ]);
        return response()->json(Ppid::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(Ppid::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $item = Ppid::findOrFail($id);
        $validated = $request->validate([
            'judul'    => 'sometimes|required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'file_url' => 'nullable|string',
            'deskripsi'=> 'nullable|string',
            'tahun'    => 'nullable|string|max:10',
            'urutan'   => 'nullable|integer',
            'aktif'    => 'nullable|boolean',
        ]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        Ppid::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
