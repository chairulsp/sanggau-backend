<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index()
    {
        return view('web.pengaduan.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'subjek'  => 'required|string|max:255',
            'pesan'   => 'required|string|max:2000',
        ]);

        Pengaduan::create([
            'nama'    => strip_tags($request->nama),
            'email'   => $request->email,
            'telepon' => $request->telepon,
            'subjek'  => strip_tags($request->subjek),
            'pesan'   => strip_tags($request->pesan),
            'status'  => 'pending',
        ]);

        return back()->with('success', 'Pengaduan berhasil dikirim. Kami akan merespons segera.');
    }
}
