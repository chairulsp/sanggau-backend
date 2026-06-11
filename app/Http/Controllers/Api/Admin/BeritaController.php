<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        // For admin list, show latest news.
        // Writers can see all news but cannot edit others' news. We will return news along with user role info if needed,
        // but simple listing is fine.
        return response()->json(Berita::with(['user', 'editor'])->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'konten'       => 'nullable|string',
            'ringkasan'    => 'nullable|string',
            'kategori'     => 'nullable|string|max:100',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'penulis'      => 'nullable|string|max:255',
            'status'       => 'nullable|in:draft,published',
            'tags'         => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/berita');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $tempPath = $file->getRealPath();
            $targetPath = $destinationPath . '/' . $filename;
            
            // Compress image using helper
            $compressed = $this->compressImage($tempPath, $targetPath, 75, 1200);
            if (!$compressed) {
                $file->move($destinationPath, $filename);
            }
            
            $validated['gambar'] = '/uploads/berita/' . $filename;
        } else {
            $validated['gambar'] = null;
        }

        $validated['slug']  = Str::slug($validated['judul']) . '-' . time();
        $validated['aktif'] = ($validated['status'] ?? 'draft') === 'published';
        
        if ($request->filled('published_at')) {
            $validated['published_at'] = \Carbon\Carbon::parse($request->published_at);
        } else {
            $validated['published_at'] = $validated['aktif'] ? now() : null;
        }
        
        unset($validated['status']);

        // Auto-generate ringkasan if empty
        if (empty($validated['ringkasan']) && !empty($validated['konten'])) {
            $plainText = strip_tags($validated['konten']);
            $validated['ringkasan'] = Str::limit($plainText, 160, '...');
        }

        // Set current user as creator
        $validated['user_id'] = $request->user()->id;
        
        // If penulis field is empty, default to current user's name
        if (empty($validated['penulis'])) {
            $validated['penulis'] = $request->user()->name;
        }

        $berita = Berita::create($validated);
        return response()->json($berita, 201);
    }

    public function show($id)
    {
        $berita = Berita::with(['user', 'editor'])->findOrFail($id);
        return response()->json($berita);
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $user = $request->user();

        // Enforce authorization checks
        if ($user->role !== 'superadmin') {
            $isAuthor = ($berita->user_id === $user->id) || 
                        ($berita->user_id === null && !empty($berita->penulis) && $berita->penulis === $user->name);
            
            if ($user->role === 'penulis') {
                if (!$isAuthor) {
                    return response()->json(['message' => 'Akses ditolak. Anda hanya dapat mengedit berita Anda sendiri.'], 403);
                }
            } elseif ($user->role === 'admin') {
                if ($berita->user_id !== null && !$isAuthor) {
                    return response()->json(['message' => 'Akses ditolak. Admin hanya dapat mengedit berita miliknya sendiri.'], 403);
                }
            } elseif ($user->role === 'editor') {
                if ($berita->user_id !== null && !$isAuthor) {
                    $author = $berita->user;
                    if (!$author || $author->role !== 'penulis') {
                        return response()->json(['message' => 'Akses ditolak. Editor hanya dapat mengedit berita milik penulis.'], 403);
                    }
                }
            }
        }

        $validated = $request->validate([
            'judul'        => 'sometimes|required|string|max:255',
            'konten'       => 'nullable|string',
            'ringkasan'    => 'nullable|string',
            'kategori'     => 'nullable|string|max:100',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'penulis'      => 'nullable|string|max:255',
            'status'       => 'nullable|in:draft,published',
            'tags'         => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/berita');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $tempPath = $file->getRealPath();
            $targetPath = $destinationPath . '/' . $filename;
            
            $compressed = $this->compressImage($tempPath, $targetPath, 75, 1200);
            if (!$compressed) {
                $file->move($destinationPath, $filename);
            }
            
            $validated['gambar'] = '/uploads/berita/' . $filename;
        } else {
            unset($validated['gambar']);
        }

        if (isset($validated['judul'])) {
            $validated['slug'] = Str::slug($validated['judul']) . '-' . $berita->id;
        }

        if (array_key_exists('status', $validated)) {
            $validated['aktif'] = $validated['status'] === 'published';
            if ($validated['aktif']) {
                $validated['published_at'] = $request->filled('published_at') ? \Carbon\Carbon::parse($request->published_at) : ($berita->published_at ?: now());
            } else {
                $validated['published_at'] = null;
            }
            unset($validated['status']);
        } else {
            if ($request->has('published_at')) {
                $validated['published_at'] = $request->filled('published_at') ? \Carbon\Carbon::parse($request->published_at) : null;
            }
        }

        // Auto-generate ringkasan if empty
        if (empty($validated['ringkasan']) && !empty($validated['konten'])) {
            $plainText = strip_tags($validated['konten']);
            $validated['ringkasan'] = Str::limit($plainText, 160, '...');
        }

        // Editor role tracking
        if ($user->role === 'editor' && $berita->user_id !== $user->id) {
            $validated['editor_id'] = $user->id;
        }

        $berita->update($validated);
        return response()->json($berita);
    }

    public function destroy(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $user = $request->user();

        // Enforce authorization checks for delete
        if ($user->role !== 'superadmin') {
            $isAuthor = ($berita->user_id === $user->id) || 
                        ($berita->user_id === null && !empty($berita->penulis) && $berita->penulis === $user->name);
            
            if ($user->role === 'penulis') {
                if (!$isAuthor) {
                    return response()->json(['message' => 'Akses ditolak. Anda hanya dapat menghapus berita Anda sendiri.'], 403);
                }
            } elseif ($user->role === 'admin') {
                if ($berita->user_id !== null && !$isAuthor) {
                    return response()->json(['message' => 'Akses ditolak. Admin hanya dapat menghapus berita miliknya sendiri.'], 403);
                }
            } elseif ($user->role === 'editor') {
                if ($berita->user_id !== null && !$isAuthor) {
                    $author = $berita->user;
                    if (!$author || $author->role !== 'penulis') {
                        return response()->json(['message' => 'Akses ditolak. Editor hanya dapat menghapus berita milik penulis.'], 403);
                    }
                }
            }
        }

        $berita->delete();
        return response()->json(null, 204);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/berita/isi');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $tempPath = $file->getRealPath();
            $targetPath = $destinationPath . '/' . $filename;
            
            // Compress inline image using helper (max width 1000px)
            $compressed = $this->compressImage($tempPath, $targetPath, 75, 1000);
            if (!$compressed) {
                $file->move($destinationPath, $filename);
            }
            
            $appUrl = config('app.url');
            if (!$appUrl || $appUrl === 'http://localhost:8000') {
                $appUrl = $request->getSchemeAndHttpHost();
            }
            $appUrl = rtrim($appUrl, '/');
            $url = $appUrl . '/uploads/berita/isi/' . $filename;
            
            return response()->json(['url' => $url]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    private function compressImage($sourcePath, $destinationPath, $quality = 75, $maxWidth = 1200)
    {
        $info = getimagesize($sourcePath);
        if ($info === false) {
            return false;
        }
        
        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($sourcePath);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Resize if width is larger than max width
        $width = imagesx($image);
        $height = imagesy($image);
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int)floor($height * ($maxWidth / $width));
            $tmpImage = imagecreatetruecolor($newWidth, $newHeight);
            
            if ($mime == 'image/png' || $mime == 'image/webp') {
                imagealphablending($tmpImage, false);
                imagesavealpha($tmpImage, true);
                $transparent = imagecolorallocatealpha($tmpImage, 255, 255, 255, 127);
                imagefilledrectangle($tmpImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            imagecopyresampled($tmpImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $tmpImage;
        }

        // Save
        if ($mime == 'image/jpeg') {
            imagejpeg($image, $destinationPath, $quality);
        } elseif ($mime == 'image/png') {
            imagepng($image, $destinationPath, 7); // 0-9 compression level
        } elseif ($mime == 'image/gif') {
            imagegif($image, $destinationPath);
        } elseif ($mime == 'image/webp') {
            imagewebp($image, $destinationPath, $quality);
        } else {
            imagejpeg($image, $destinationPath, $quality);
        }

        imagedestroy($image);
        return true;
    }
}
