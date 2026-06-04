<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laman;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LamanController extends Controller
{
    public function index()
    {
        return response()->json(Laman::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'konten' => 'nullable|string',
            'aktif'  => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['judul']) . '-' . time();
        
        $laman = Laman::create($validated);
        return response()->json($laman, 201);
    }

    public function show($id)
    {
        $laman = Laman::findOrFail($id);
        return response()->json($laman);
    }

    public function update(Request $request, $id)
    {
        $laman = Laman::findOrFail($id);

        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'konten' => 'nullable|string',
            'aktif'  => 'boolean'
        ]);

        if (isset($validated['judul']) && $validated['judul'] !== $laman->judul) {
            $validated['slug'] = Str::slug($validated['judul']) . '-' . time();
        }

        $laman->update($validated);
        return response()->json($laman);
    }

    public function destroy($id)
    {
        $laman = Laman::findOrFail($id);
        $laman->delete();
        return response()->json(null, 204);
    }
}
