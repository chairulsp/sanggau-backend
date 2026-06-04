<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginHistory::orderBy('created_at', 'desc');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%")
                  ->orWhere('device', 'like', "%{$search}%")
                  ->orWhere('os', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $histories = $query->paginate($perPage);

        return response()->json($histories);
    }

    public function destroy($id)
    {
        $history = LoginHistory::findOrFail($id);
        $history->delete();

        return response()->json(['message' => 'Riwayat login berhasil dihapus.']);
    }
}
