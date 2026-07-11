<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->input('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->format('Y-m-d'));

        $transaksiSelesai = Transaksi::whereIn('status', ['selesai', 'diambil'])
            ->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59']);

        $totalPendapatan = (clone $transaksiSelesai)->sum('total');
        $totalTransaksi = (clone $transaksiSelesai)->count();
        $totalBerat = (clone $transaksiSelesai)->sum('berat');
        $rataTransaksi = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        $pelangganBaru = Pelanggan::whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])->count();
        $pelangganAktif = Transaksi::whereIn('status', ['selesai', 'diambil'])
            ->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
            ->distinct('pelanggan_id')
            ->count('pelanggan_id');

        $laporanLayanan = Layanan::withCount(['transaksis as total_transaksi' => function ($q) use ($dari, $sampai) {
            $q->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59']);
        }])->withSum(['transaksis as total_pendapatan' => function ($q) use ($dari, $sampai) {
            $q->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59']);
        }], 'total')->get();

        foreach ($laporanLayanan as $l) {
            $l->total_berat = Transaksi::where('layanan_id', $l->id)
                ->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
                ->sum('berat');
        }

        return view('admin.laporan.index', compact(
            'totalPendapatan', 'totalTransaksi', 'totalBerat', 'rataTransaksi',
            'pelangganBaru', 'pelangganAktif', 'laporanLayanan',
            'dari', 'sampai'
        ));
    }

    public function pendapatan(Request $request)
    {
        return redirect()->route('admin.laporan.index', ['tab' => 'pendapatan'] + $request->all());
    }

    public function pelanggan(Request $request)
    {
        return redirect()->route('admin.laporan.index', ['tab' => 'pelanggan'] + $request->all());
    }

    public function layanan(Request $request)
    {
        return redirect()->route('admin.laporan.index', ['tab' => 'layanan'] + $request->all());
    }
}
