<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Carbon\Carbon;

class VisitorController extends Controller
{
    /**
     * Catat kunjungan baru
     */
    public function track(Request $request)
    {
        $ua = $request->userAgent() ?? '';
        $ip = $request->ip();
        $sessionId = $request->input('session_id', '');

        if (!$sessionId) {
            return response()->json(['ok' => false], 400);
        }

        // Deteksi device
        $device = 'desktop';
        if (preg_match('/Mobile|Android|iPhone|iPad/i', $ua)) {
            $device = preg_match('/iPad/i', $ua) ? 'tablet' : 'mobile';
        }

        // Deteksi browser
        $browser = 'Other';
        if (str_contains($ua, 'Chrome') && !str_contains($ua, 'Edg')) $browser = 'Chrome';
        elseif (str_contains($ua, 'Firefox')) $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari') && !str_contains($ua, 'Chrome')) $browser = 'Safari';
        elseif (str_contains($ua, 'Edg')) $browser = 'Edge';
        elseif (str_contains($ua, 'Opera') || str_contains($ua, 'OPR')) $browser = 'Opera';

        // Deteksi OS
        $os = 'Other';
        if (str_contains($ua, 'Windows')) $os = 'Windows';
        elseif (str_contains($ua, 'Mac')) $os = 'MacOS';
        elseif (str_contains($ua, 'Android')) $os = 'Android';
        elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) $os = 'iOS';
        elseif (str_contains($ua, 'Linux')) $os = 'Linux';

        // Cek apakah session sudah ada hari ini
        $existing = Visitor::where('session_id', $sessionId)
            ->whereDate('created_at', today())
            ->first();

        $isNew = !$existing;

        Visitor::create([
            'session_id'  => $sessionId,
            'ip_address'  => $ip,
            'halaman'     => $request->input('halaman', '/'),
            'referrer'    => $request->input('referrer', ''),
            'user_agent'  => substr($ua, 0, 500),
            'device'      => $device,
            'browser'     => $browser,
            'os'          => $os,
            'is_new'      => $isNew,
        ]);

        return response()->json(['ok' => true, 'is_new' => $isNew]);
    }

    /**
     * Statistik untuk dashboard admin
     */
    public function stats()
    {
        $now = Carbon::now();

        // Online: kunjungan dalam 5 menit terakhir (unique session)
        $online = Visitor::where('created_at', '>=', $now->copy()->subMinutes(5))
            ->distinct('session_id')
            ->count('session_id');

        // Hari ini
        $hariIni = Visitor::whereDate('created_at', today())
            ->distinct('session_id')
            ->count('session_id');

        // Kemarin
        $kemarin = Visitor::whereDate('created_at', $now->copy()->subDay())
            ->distinct('session_id')
            ->count('session_id');

        // 7 hari
        $tujuhHari = Visitor::where('created_at', '>=', $now->copy()->subDays(7))
            ->distinct('session_id')
            ->count('session_id');

        // 30 hari
        $tigaPuluhHari = Visitor::where('created_at', '>=', $now->copy()->subDays(30))
            ->distinct('session_id')
            ->count('session_id');

        // Total
        $total = Visitor::distinct('session_id')->count('session_id');

        // Per halaman (top 5)
        $topHalaman = Visitor::select('halaman', \DB::raw('count(*) as total'))
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('halaman')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Per device
        $perDevice = Visitor::select('device', \DB::raw('count(*) as total'))
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('device')
            ->get();

        // Per browser
        $perBrowser = Visitor::select('browser', \DB::raw('count(*) as total'))
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Grafik 7 hari terakhir
        $grafik = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $grafik[] = [
                'tanggal' => $date->format('d M'),
                'pengunjung' => Visitor::whereDate('created_at', $date)
                    ->distinct('session_id')
                    ->count('session_id'),
            ];
        }

        return response()->json([
            'online'          => $online,
            'hari_ini'        => $hariIni,
            'kemarin'         => $kemarin,
            'tujuh_hari'      => $tujuhHari,
            'tiga_puluh_hari' => $tigaPuluhHari,
            'total'           => $total,
            'top_halaman'     => $topHalaman,
            'per_device'      => $perDevice,
            'per_browser'     => $perBrowser,
            'grafik'          => $grafik,
        ]);
    }
}
