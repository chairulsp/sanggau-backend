<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\ProfilDiskominfo;
use App\Models\Pegawai;
use App\Models\StrukturOrganisasi;

class ProfilController extends Controller
{
    public function index()
    {
        $profil       = ProfilDiskominfo::first();
        $pimpinan     = Pegawai::where('tipe_jabatan','pimpinan')->where('aktif',true)->orderBy('urutan')->get();
        $fungsional   = Pegawai::where('tipe_jabatan','fungsional')->where('aktif',true)->orderBy('urutan')->get();
        $pelaksana    = Pegawai::where('tipe_jabatan','pelaksana')->where('aktif',true)->orderBy('urutan')->get();
        $struktur     = StrukturOrganisasi::where('aktif',true)->orderBy('urutan')->get();
        $pegawai = Pegawai::where('aktif', true)->orderBy('urutan')->get();
        return view('web.profil.index', compact('profil','pegawai'));
    }
}
