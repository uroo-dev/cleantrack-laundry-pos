<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $transaksis = Transaksi::with(['pelanggan', 'layanan', 'user'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];

        return view('admin.transaksi.index', compact('transaksis', 'status', 'dateFrom', 'dateTo', 'statuses'));
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'layanan', 'user', 'detailLaundries', 'trackings', 'pembayaran']);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function export(Request $request)
    {
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $transaksis = Transaksi::with(['pelanggan', 'layanan'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->get();

        // TODO: Implement PDF/Excel export using a package like barryvdh/laravel-dompdf or maatwebsite/laravel-excel
        // For now, return a printable view
        return view('admin.transaksi.export', compact('transaksis'));
    }
}
