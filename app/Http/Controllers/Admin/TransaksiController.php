<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\DetailLaundry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $dateFrom = $request->input('dari');
        $dateTo = $request->input('sampai');

        $transaksi = Transaksi::with(['pelanggan', 'layanan', 'user'])
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

        $totalInvoice = Transaksi::count();
        $belumLunas = Transaksi::where('status_pembayaran', '!=', 'lunas')->count();
        $totalPendapatan = Transaksi::where('status_pembayaran', 'lunas')->sum('total');

        return view('admin.transaksi.index', compact(
            'transaksi', 'status', 'dateFrom', 'dateTo',
            'totalInvoice', 'belumLunas', 'totalPendapatan'
        ));
    }

    public function create()
    {
        $pelanggan = Pelanggan::withCount('transaksis')->latest()->get();
        $layanan = Layanan::where('is_active', true)->get();

        return view('admin.transaksi.create', compact('pelanggan', 'layanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggans,id'],
            'layanan_id' => ['required', 'array'],
            'layanan_id.*' => ['exists:layanans,id'],
            'berat' => ['required', 'array'],
            'berat.*' => ['numeric', 'min:0.1'],
            'diskon' => ['nullable', 'numeric', 'min:0'],
            'dp' => ['nullable', 'numeric', 'min:0'],
            'status_pembayaran' => ['required', 'in:lunas,dp,belum'],
            'estimasi_selesai' => ['nullable', 'date'],
            'outlet' => ['nullable', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $totalBerat = array_sum($validated['berat']);
            $firstLayanan = Layanan::find($validated['layanan_id'][0]);
            $hargaPerKg = $firstLayanan->harga_perkg ?? 0;
            $total = $totalBerat * $hargaPerKg;
            $diskon = $validated['diskon'] ?? 0;
            $total -= $diskon;

            $lastTransaksi = Transaksi::withTrashed()
                ->where('kode_transaksi', 'like', 'INV-' . date('Ymd') . '-%')
                ->orderBy('kode_transaksi', 'desc')
                ->first();
            $lastNumber = $lastTransaksi ? (int) substr($lastTransaksi->kode_transaksi, -4) : 0;
            $kodeTransaksi = 'INV-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'pelanggan_id' => $validated['pelanggan_id'],
                'user_id' => auth()->id(),
                'layanan_id' => $validated['layanan_id'][0],
                'berat' => $totalBerat,
                'harga' => $hargaPerKg,
                'diskon' => $diskon,
                'dp' => $validated['dp'] ?? 0,
                'total' => max(0, $total),
                'status' => 'pending',
                'status_pembayaran' => $validated['status_pembayaran'],
                'catatan' => $validated['catatan'] ?? null,
                'outlet' => $validated['outlet'] ?? 'Utama',
                'estimasi_selesai' => $validated['estimasi_selesai'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.transaksi.show', $transaksi->id)
                ->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'layanan', 'user', 'detailLaundries']);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,proses,selesai,diambil'],
        ]);

        try {
            $transaksi->update(['status' => $validated['status']]);

            if ($validated['status'] === 'diambil') {
                $transaksi->update(['tanggal_ambil' => now()]);
            }

            return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function updatePayment(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'status_pembayaran' => ['required', 'in:belum,dp,lunas'],
            'dp' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $data = ['status_pembayaran' => $validated['status_pembayaran']];
            if (isset($validated['dp'])) {
                $data['dp'] = $validated['dp'];
            }
            $transaksi->update($data);

            return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pembayaran: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $status = $request->input('status');
        $dateFrom = $request->input('dari');
        $dateTo = $request->input('sampai');

        $transaksi = Transaksi::with(['pelanggan', 'layanan'])
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

        return view('admin.transaksi.export', compact('transaksi'));
    }
}
