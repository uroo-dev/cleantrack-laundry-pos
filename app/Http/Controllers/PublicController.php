<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $layanans = Layanan::where('is_active', true)->get();
        $totalPelanggan = Pelanggan::count();

        return view('public.home', compact('layanans', 'totalPelanggan'));
    }

    public function tracking(Request $request, $kode = null)
    {
        $transaksi = null;

        $kode = $kode ?? $request->input('kode');

        if ($kode) {
            $transaksi = Transaksi::where('kode_transaksi', $kode)
                ->with(['layanan', 'trackings' => function ($query) {
                    $query->orderBy('waktu', 'asc');
                }, 'detailLaundries'])
                ->first();
        }

        return view('public.tracking', compact('transaksi'));
    }

    public function downloadNota($kodeTransaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)
            ->with(['pelanggan', 'layanan', 'detailLaundries'])
            ->firstOrFail();

        return view('staff.order.nota', compact('transaksi'));
    }
}
