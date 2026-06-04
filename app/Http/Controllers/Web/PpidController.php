<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Ppid;

class PpidController extends Controller
{
    public function index()
    {
        $ppid = Ppid::where('aktif', true)->orderBy('urutan')->get();
        return view('web.ppid.index', compact('ppid'));
    }
}
