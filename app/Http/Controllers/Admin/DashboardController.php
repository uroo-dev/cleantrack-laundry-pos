<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->sum('total');

        $totalPelanggan = Pelanggan::count();

        $orderAktif = Transaksi::whereNotIn('status', ['selesai', 'diambil'])->count();

        $laundrySelesai = Transaksi::where('status', 'selesai')->count();

        $transaksiTerbaru = Transaksi::with(['pelanggan', 'layanan'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'pendapatanHariIni',
            'totalPelanggan',
            'orderAktif',
            'laundrySelesai',
            'transaksiTerbaru'
        ));
    }
}
