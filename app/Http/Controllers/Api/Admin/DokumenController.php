<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index()
    {
        return response()->json(Dokumen::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'file_url' => 'nullable|string',
            'file'     => 'nullable|file|max:20480', // limit to 20MB
            'deskripsi'=> 'nullable|string',
            'tahun'    => 'nullable|string|max:10',
            'aktif'    => 'nullable|boolean',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/dokumen'), $filename);
            $validated['file_url'] = '/uploads/dokumen/' . $filename;
        }

        unset($validated['file']);
        return response()->json(Dokumen::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(Dokumen::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $item = Dokumen::findOrFail($id);
        $validated = $request->validate([
            'judul'    => 'sometimes|required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'file_url' => 'nullable|string',
            'file'     => 'nullable|file|max:20480', // limit to 20MB
            'deskripsi'=> 'nullable|string',
            'tahun'    => 'nullable|string|max:10',
            'aktif'    => 'nullable|boolean',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/dokumen'), $filename);
            $validated['file_url'] = '/uploads/dokumen/' . $filename;
        }

        unset($validated['file']);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        Dokumen::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
