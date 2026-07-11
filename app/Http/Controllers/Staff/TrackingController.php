<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Tracking;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $orders = Transaksi::with(['pelanggan', 'layanan'])
            ->whereNotIn('status', ['selesai', 'diambil'])
            ->orderBy('created_at')
            ->get();

        $transaksi = null;
        if ($request->filled('kode')) {
            $transaksi = Transaksi::where('kode_transaksi', $request->kode)
                ->with(['pelanggan', 'layanan', 'trackings' => function ($q) {
                    $q->orderBy('waktu', 'asc');
                }, 'detailLaundries'])
                ->first();
        }

        return view('staff.tracking.index', compact('orders', 'transaksi'));
    }

    public function updateProgres(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'status'     => ['required', 'string', 'in:menunggu,dicuci,dikeringkan,disetrika,selesai,diambil'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $statusFlow = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];

        $currentIndex = array_search($transaksi->status, $statusFlow);
        $newIndex = array_search($validated['status'], $statusFlow);

        if ($newIndex !== false && $newIndex < $currentIndex && $validated['status'] !== 'dibatalkan') {
            return back()->with('error', 'Tidak dapat mundur ke status sebelumnya.');
        }

        try {
            DB::beginTransaction();

            $transaksi->update(['status' => $validated['status']]);

            $keterangan = $validated['keterangan'] ?? match ($validated['status']) {
                'dicuci'       => 'Pakaian sedang dicuci',
                'dikeringkan'  => 'Pakaian sedang dikeringkan',
                'disetrika'    => 'Pakaian sedang disetrika',
                'selesai'      => 'Pakaian telah selesai',
                'diambil'      => 'Pakaian telah diambil',
                default        => 'Status diperbarui ke ' . $validated['status'],
            };

            Tracking::create([
                'transaksi_id' => $transaksi->id,
                'status'       => $validated['status'],
                'keterangan'   => $keterangan,
                'waktu'        => now(),
            ]);

            if ($validated['status'] === 'selesai') {
                $transaksi->update(['tanggal_ambil' => now()]);

                $pelanggan = $transaksi->pelanggan;
                if ($pelanggan) {
                    $pelanggan->increment('poin', 1);
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Progress berhasil diperbarui.',
                    'data'    => [
                        'transaksi' => $transaksi->fresh(),
                        'tracking'  => $transaksi->trackings()->latest()->first(),
                    ],
                ]);
            }

            return back()->with('success', 'Progress berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui progress: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal memperbarui progress: ' . $e->getMessage());
        }
    }

    public function getEstimasi(Request $request, $id)
    {
        $validated = $request->validate([
            'layanan_id' => ['required', 'exists:layanans,id'],
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);

        $estimasiSelesai = now()->addDays($layanan->estimasi_hari);

        return response()->json([
            'success' => true,
            'data'    => [
                'estimasi_hari'    => $layanan->estimasi_hari,
                'estimasi_selesai' => $estimasiSelesai->format('Y-m-d H:i:s'),
                'tanggal_selesai'  => $estimasiSelesai->format('d/m/Y'),
            ],
        ]);
    }
}
