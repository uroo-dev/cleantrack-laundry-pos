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
        $yesterday = Carbon::yesterday();

        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->sum('total');

        $pendapatanKemarin = Transaksi::whereDate('created_at', $yesterday)
            ->where('status', 'selesai')
            ->sum('total');

        $totalPelanggan = Pelanggan::count();

        $orderAktif = Transaksi::whereNotIn('status', ['selesai', 'diambil'])->count();

        $laundrySelesai = Transaksi::where('status', 'selesai')
            ->whereDate('updated_at', $today)
            ->count();

        $transaksiTerbaru = Transaksi::with('pelanggan')
            ->latest()
            ->take(10)
            ->get();

        $omzetGrowth = $pendapatanKemarin > 0
            ? round((($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100)
            : ($pendapatanHariIni > 0 ? 100 : 0);

        $pelangganBaru = Pelanggan::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $cucianJatuhTempo = Transaksi::with('pelanggan')
            ->whereNotIn('status', ['selesai', 'diambil'])
            ->whereDate('estimasi_selesai', '<=', $today)
            ->orderBy('estimasi_selesai')
            ->take(5)
            ->get();

        $chartDays = 7;
        $chartLabels = [];
        $chartData = [];
        for ($i = $chartDays - 1; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $chartLabels[] = $day->format('D');
            $chartData[] = Transaksi::whereDate('created_at', $day)->count();
        }

        return view('admin.dashboard', compact(
            'pendapatanHariIni',
            'totalPelanggan',
            'orderAktif',
            'laundrySelesai',
            'transaksiTerbaru',
            'omzetGrowth',
            'pelangganBaru',
            'cucianJatuhTempo',
            'chartLabels',
            'chartData'
        ));
    }
}
