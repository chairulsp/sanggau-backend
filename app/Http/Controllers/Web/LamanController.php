<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Laman;

class LamanController extends Controller
{
    public function show($slug)
    {
        $laman = Laman::where('slug', $slug)->where('aktif', true)->firstOrFail();
        return view('web.laman.show', compact('laman'));
    }
}
