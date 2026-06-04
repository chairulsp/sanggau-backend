<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\ProfilDiskominfo;

class KontakController extends Controller
{
    public function index()
    {
        $profil = ProfilDiskominfo::first();
        return view('web.kontak.index', compact('profil'));
    }
}
