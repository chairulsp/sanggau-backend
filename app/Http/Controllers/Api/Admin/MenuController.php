<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return response()->json(Menu::orderBy('urutan', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'ikon' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable|boolean',
            'buka_tab_baru' => 'nullable|boolean',
        ]);
        return response()->json(Menu::create($validated), 201);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'ikon' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable|boolean',
            'buka_tab_baru' => 'nullable|boolean',
        ]);
        $menu->update($validated);
        return response()->json($menu);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(null, 204);
    }
}
