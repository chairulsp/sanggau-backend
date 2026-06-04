<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pengaduan;

class PengaduanAdminController extends Controller
{
    public function index()
    {
        return response()->json(Pengaduan::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        return response()->json(Pengaduan::findOrFail($id));
    }

    public function balas(Request $request, $id)
    {
        $request->validate(['balasan' => 'required|string']);
        $item = Pengaduan::findOrFail($id);
        $item->update([
            'balasan'    => $request->balasan,
            'status'     => 'selesai',
            'dibalas_at' => now(),
        ]);
        return response()->json(['message' => 'Balasan berhasil dikirim.', 'data' => $item]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:baru,diproses,selesai']);
        $item = Pengaduan::findOrFail($id);
        $item->update(['status' => $request->status]);
        return response()->json(['message' => 'Status diperbarui.', 'data' => $item]);
    }

    public function destroy($id)
    {
        Pengaduan::findOrFail($id)->delete();
        return response()->json(['message' => 'Pengaduan berhasil dihapus.']);
    }
}
