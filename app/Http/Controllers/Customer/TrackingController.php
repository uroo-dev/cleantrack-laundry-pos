<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Rating;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function cekStatus(Request $request)
    {
        $validated = $request->validate([
            'kode_transaksi' => ['required', 'string', 'exists:transaksis,kode_transaksi'],
        ]);

        $transaksi = Transaksi::where('kode_transaksi', $validated['kode_transaksi'])
            ->with(['layanan', 'trackings' => function ($query) {
                $query->orderBy('waktu', 'asc');
            }, 'detailLaundries'])
            ->first();

        if (!$transaksi) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $transaksi,
            ]);
        }

        $emojis = ['●', '●', '●', '●', '●', '●'];

        return view('customer.tracking', compact('transaksi', 'emojis'));
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $pelanggan = Pelanggan::where('email', $user->email)->first();

        if (!$pelanggan) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan.'], 404);
            }

            return back()->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $transaksis = Transaksi::where('pelanggan_id', $pelanggan->id)
            ->with('layanan')
            ->latest()
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $transaksis,
            ]);
        }

        return redirect()->route('customer.dashboard');
    }

    public function notification(Request $request)
    {
        $validated = $request->validate([
            'kode_transaksi' => ['required', 'string', 'exists:transaksis,kode_transaksi'],
        ]);

        $transaksi = Transaksi::where('kode_transaksi', $validated['kode_transaksi'])
            ->with(['trackings' => function ($query) {
                $query->orderBy('waktu', 'desc')->limit(5);
            }])
            ->first();

        $latestTracking = $transaksi->trackings->first();

        $message = match ($transaksi->status) {
            'menunggu'    => 'Pesanan Anda sedang menunggu diproses.',
            'dicuci'      => 'Laundry Anda sedang dicuci.',
            'dikeringkan' => 'Laundry Anda sedang dikeringkan.',
            'disetrika'   => 'Laundry Anda sedang disetrika.',
            'selesai'     => 'Laundry Anda telah selesai! Silahkan diambil.',
            'diambil'     => 'Laundry Anda telah diambil. Terima kasih!',
            default       => 'Status terbaru: ' . $transaksi->status,
        };

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => [
                    'status'       => $transaksi->status,
                    'message'      => $message,
                    'trackings'    => $transaksi->trackings,
                    'last_update'  => $latestTracking ? $latestTracking->waktu : null,
                ],
            ]);
        }

        return back()->with('info', $message);
    }

    public function rate(Request $request)
    {
        $validated = $request->validate([
            'kode_transaksi' => ['required', 'string', 'exists:transaksis,kode_transaksi'],
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan'         => ['nullable', 'string', 'max:500'],
        ]);

        $transaksi = Transaksi::where('kode_transaksi', $validated['kode_transaksi'])->first();

        if (!in_array($transaksi->status, ['selesai', 'diambil'])) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya transaksi selesai yang dapat diberi rating.',
                ], 422);
            }

            return back()->with('error', 'Hanya transaksi selesai yang dapat diberi rating.');
        }

        $existingRating = Rating::where('transaksi_id', $transaksi->id)->first();
        if ($existingRating) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan rating untuk transaksi ini.',
                ], 422);
            }

            return back()->with('error', 'Anda sudah memberikan rating untuk transaksi ini.');
        }

        try {
            Rating::create([
                'transaksi_id' => $transaksi->id,
                'rating'       => $validated['rating'],
                'review'       => $validated['ulasan'] ?? null,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Terima kasih atas rating Anda!',
                ]);
            }

            return back()->with('success', 'Terima kasih atas rating Anda!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan rating: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menyimpan rating: ' . $e->getMessage());
        }
    }
}
