<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return response()->json(Banner::orderBy('urutan', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'gambar'   => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link'     => 'nullable|string|max:255',
            'posisi'   => 'nullable|string|max:255',
            'urutan'   => 'nullable|integer',
            'aktif'    => 'nullable|in:0,1,true,false',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            
            // Validasi file valid
            if ($file->isValid()) {
                // Pastikan folder exists
                $uploadPath = public_path('uploads/banner');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }
                
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Try move file
                try {
                    $file->move($uploadPath, $filename);
                    $validated['gambar'] = '/uploads/banner/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Gagal upload gambar: ' . $e->getMessage()
                    ], 500);
                }
            } else {
                return response()->json([
                    'message' => 'File gambar tidak valid: ' . $file->getErrorMessage()
                ], 422);
            }
        }

        // Konversi aktif ke boolean
        if (isset($validated['aktif'])) {
            $validated['aktif'] = filter_var($validated['aktif'], FILTER_VALIDATE_BOOLEAN);
        }

        $banner = Banner::create($validated);
        return response()->json($banner, 201);
    }

    public function show(Banner $banner)
    {
        return response()->json($banner);
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link'     => 'nullable|string|max:255',
            'posisi'   => 'nullable|string|max:255',
            'urutan'   => 'nullable|integer',
            'aktif'    => 'nullable|in:0,1,true,false',
        ]);

        // Konversi aktif ke boolean
        if (isset($validated['aktif'])) {
            $validated['aktif'] = filter_var($validated['aktif'], FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            
            // Validasi file valid
            if ($file->isValid()) {
                // Pastikan folder exists
                $uploadPath = public_path('uploads/banner');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }
                
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Try move file
                try {
                    $file->move($uploadPath, $filename);
                    $validated['gambar'] = '/uploads/banner/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Gagal upload gambar: ' . $e->getMessage()
                    ], 500);
                }
            }
        } else {
            unset($validated['gambar']);
        }

        $banner->update($validated);
        return response()->json($banner);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response()->json(null, 204);
    }
}
