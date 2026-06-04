<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriVideo;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return response()->json(GaleriVideo::orderBy('urutan')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:255',
            'url_youtube' => 'required|string',
            'deskripsi'   => 'nullable|string',
            'tanggal'     => 'nullable|date',
            'urutan'      => 'nullable|integer',
            'aktif'       => 'nullable|boolean',
        ]);
        return response()->json(GaleriVideo::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(GaleriVideo::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $item = GaleriVideo::findOrFail($id);
        $validated = $request->validate([
            'judul'       => 'sometimes|required|string|max:255',
            'url_youtube' => 'sometimes|required|string',
            'deskripsi'   => 'nullable|string',
            'tanggal'     => 'nullable|date',
            'urutan'      => 'nullable|integer',
            'aktif'       => 'nullable|boolean',
        ]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        GaleriVideo::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
