<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('email', $user->email)->first();

        if (!$pelanggan) {
            return view('customer.dashboard', [
                'pelanggan'    => null,
                'laundryAktif' => 0,
                'totalRiwayat' => 0,
                'riwayat'      => collect(),
            ]);
        }

        $laundryAktif = Transaksi::where('pelanggan_id', $pelanggan->id)
            ->whereNotIn('status', ['selesai', 'diambil', 'dibatalkan'])
            ->count();

        $totalRiwayat = Transaksi::where('pelanggan_id', $pelanggan->id)->count();

        $riwayat = Transaksi::where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['selesai', 'diambil'])
            ->with('layanan')
            ->latest()
            ->take(10)
            ->get();

        return view('customer.dashboard', compact(
            'pelanggan',
            'laundryAktif',
            'totalRiwayat',
            'riwayat'
        ));
    }

    public function tracking(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'layanan', 'trackings' => function ($query) {
            $query->orderBy('waktu', 'asc');
        }, 'detailLaundries', 'pembayaran']);

        return view('customer.tracking', compact('transaksi'));
    }
}
