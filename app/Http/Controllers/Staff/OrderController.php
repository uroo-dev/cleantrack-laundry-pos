<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DetailLaundry;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Tracking;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $layanan = Layanan::where('is_active', true)->get();
        $pelanggan = Pelanggan::latest()->take(10)->get();

        return view('staff.order.index', compact('layanan', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggans,id'],
            'layanan_id'   => ['required', 'exists:layanans,id'],
            'berat'        => ['required', 'numeric', 'min:0.1'],
            'diskon'       => ['nullable', 'numeric', 'min:0'],
            'catatan'      => ['nullable', 'string', 'max:1000'],
            'items'        => ['nullable', 'array'],
            'items.*.nama_barang' => ['required_with:items', 'string', 'max:255'],
            'items.*.jumlah'      => ['required_with:items', 'integer', 'min:1'],
            'items.*.catatan'     => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $layanan = Layanan::findOrFail($validated['layanan_id']);
            $harga = $layanan->harga_perkg * $validated['berat'];
            $diskon = $validated['diskon'] ?? 0;
            $total = $harga - $diskon;

            $datePrefix = 'LDR-' . date('Ymd') . '-';
            $lastTransaksi = Transaksi::where('kode_transaksi', 'like', $datePrefix . '%')
                ->orderBy('kode_transaksi', 'desc')
                ->first();

            $lastNumber = $lastTransaksi ? (int) substr($lastTransaksi->kode_transaksi, -3) : 0;
            $kodeTransaksi = $datePrefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            $estimasiSelesai = now()->addDays($layanan->estimasi_hari);

            $transaksi = Transaksi::create([
                'kode_transaksi'  => $kodeTransaksi,
                'pelanggan_id'    => $validated['pelanggan_id'],
                'user_id'         => Auth::id(),
                'layanan_id'      => $validated['layanan_id'],
                'berat'           => $validated['berat'],
                'harga'           => $harga,
                'diskon'          => $diskon,
                'total'           => $total,
                'status'          => 'menunggu',
                'catatan'         => $validated['catatan'] ?? null,
                'estimasi_selesai' => $estimasiSelesai,
            ]);

            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    DetailLaundry::create([
                        'transaksi_id' => $transaksi->id,
                        'nama_barang'  => $item['nama_barang'],
                        'jumlah'       => $item['jumlah'],
                        'catatan'      => $item['catatan'] ?? null,
                    ]);
                }
            }

            Tracking::create([
                'transaksi_id' => $transaksi->id,
                'status'       => 'menunggu',
                'keterangan'   => 'Pesanan baru diterima',
                'waktu'        => now(),
            ]);

            $pelanggan = Pelanggan::find($validated['pelanggan_id']);
            $pelanggan->increment('total_transaksi');

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order berhasil dibuat.',
                    'data'    => $transaksi,
                ]);
            }

            return redirect()->route('staff.order.queue')
                ->with('success', "Order {$kodeTransaksi} berhasil dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat order: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }

    public function createPelanggan(Request $request)
    {
        $validated = $request->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20'],
            'alamat'  => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $lastPelanggan = Pelanggan::withTrashed()
                ->where('kode_pelanggan', 'like', 'PLG-' . date('Ymd') . '-%')
                ->orderBy('kode_pelanggan', 'desc')
                ->first();

            $lastNumber = $lastPelanggan ? (int) substr($lastPelanggan->kode_pelanggan, -3) : 0;
            $kodePelanggan = 'PLG-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            $pelanggan = Pelanggan::create([
                'kode_pelanggan'  => $kodePelanggan,
                'nama'            => $validated['nama'],
                'telepon'         => $validated['telepon'],
                'alamat'          => $validated['alamat'] ?? null,
                'poin'            => 0,
                'total_transaksi' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil ditambahkan.',
                'data'    => $pelanggan,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pelanggan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getPelanggan(Request $request)
    {
        $search = $request->input('search');

        $pelanggans = Pelanggan::where('nama', 'like', "%{$search}%")
            ->orWhere('kode_pelanggan', 'like', "%{$search}%")
            ->orWhere('telepon', 'like', "%{$search}%")
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $pelanggans,
        ]);
    }

    public function queue()
    {
        $statuses = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];

        $orders = Transaksi::with(['pelanggan', 'layanan'])
            ->orderByRaw("FIELD(status, 'menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil')")
            ->latest()
            ->get();

        $groups = $orders->groupBy('status');

        return view('staff.order.queue', compact('orders', 'groups', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status'     => ['required', 'string', 'in:menunggu,dicuci,dikeringkan,disetrika,selesai,diambil'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $transaksi = Transaksi::findOrFail($id);

        try {
            DB::beginTransaction();

            $transaksi->update(['status' => $validated['status']]);

            $keterangan = $validated['keterangan'] ?? match ($validated['status']) {
                'dicuci'       => 'Laundry sedang dicuci',
                'dikeringkan'  => 'Laundry sedang dikeringkan',
                'disetrika'    => 'Laundry sedang disetrika',
                'selesai'      => 'Laundry selesai',
                'diambil'      => 'Laundry sudah diambil',
                default        => 'Status diperbarui',
            };

            Tracking::create([
                'transaksi_id' => $transaksi->id,
                'status'       => $validated['status'],
                'keterangan'   => $keterangan,
                'waktu'        => now(),
            ]);

            if ($validated['status'] === 'diambil') {
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
                    'message' => 'Status berhasil diperbarui.',
                ]);
            }

            return back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function cetakNota($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'layanan', 'detailLaundries'])->findOrFail($id);

        return view('staff.order.nota', compact('transaksi'));
    }

    public function printNota($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'layanan', 'detailLaundries'])->findOrFail($id);

        return view('staff.order.nota', compact('transaksi'));
    }
}
