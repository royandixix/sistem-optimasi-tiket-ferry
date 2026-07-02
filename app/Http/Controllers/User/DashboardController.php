<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PemesananTiket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $penumpang = $user->penumpang;

        $totalPemesanan = 0;
        $pending = 0;
        $diterima = 0;
        $ditolak = 0;
        $pemesananTerbaru = collect();

        if ($penumpang) {
            $query = PemesananTiket::query()
                ->where('penumpang_id', $penumpang->id);

            $totalPemesanan = (clone $query)->count();
            $pending = (clone $query)->where('status_pemesanan', 'pending')->count();
            $diterima = (clone $query)->where('status_pemesanan', 'diterima')->count();
            $ditolak = (clone $query)->where('status_pemesanan', 'ditolak')->count();

            $pemesananTerbaru = (clone $query)
                ->with(['jadwal.kapal', 'jadwal.rute'])
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('user.dashboard', compact(
            'user',
            'penumpang',
            'totalPemesanan',
            'pending',
            'diterima',
            'ditolak',
            'pemesananTerbaru'
        ));
    }
}