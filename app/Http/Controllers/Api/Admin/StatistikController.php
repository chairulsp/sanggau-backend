<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statistik;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function index()
    {
        return response()->json(Statistik::orderBy('urutan', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'ikon' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable|boolean'
        ]);

        $statistik = Statistik::create($validated);
        return response()->json($statistik, 201);
    }

    public function show(Statistik $statistik)
    {
        return response()->json($statistik);
    }

    public function update(Request $request, Statistik $statistik)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'ikon' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable|boolean'
        ]);

        $statistik->update($validated);
        return response()->json($statistik);
    }

    public function destroy(Statistik $statistik)
    {
        $statistik->delete();
        return response()->json(null, 204);
    }
}
