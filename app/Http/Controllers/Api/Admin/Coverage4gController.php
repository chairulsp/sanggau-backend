<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coverage4g;
use Illuminate\Http\Request;

class Coverage4gController extends Controller
{
    // GET /api/admin/coverage4g
    public function index()
    {
        return response()->json(Coverage4g::orderBy('urutan')->get());
    }

    // PUT /api/admin/coverage4g/{id}
    public function update(Request $request, Coverage4g $coverage4g)
    {
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:100',
            'ibu_kota'  => 'nullable|string|max:100',
            'persen'    => 'required|integer|min:0|max:100',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $coverage4g->update($validated);

        return response()->json($coverage4g);
    }

    // POST /api/admin/coverage4g/bulk  — update semua sekaligus
    public function bulk(Request $request)
    {
        $request->validate([
            'data'              => 'required|array',
            'data.*.id'         => 'required|integer|exists:coverage4g,id',
            'data.*.persen'     => 'required|integer|min:0|max:100',
            'data.*.ibu_kota'   => 'nullable|string|max:100',
        ]);

        foreach ($request->data as $item) {
            Coverage4g::where('id', $item['id'])->update([
                'persen'   => $item['persen'],
                'ibu_kota' => $item['ibu_kota'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data coverage 4G berhasil diperbarui.',
            'data'    => Coverage4g::orderBy('urutan')->get(),
        ]);
    }
}
