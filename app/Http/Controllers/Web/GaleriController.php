<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriVideo;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Galeri::where('aktif', true)
            ->orderByDesc('created_at')
            ->paginate(20);

        $video = GaleriVideo::where('aktif', true)
            ->orderByDesc('created_at')
            ->get();

        return view('web.galeri.index', compact('galeri', 'video'));
    }
}
