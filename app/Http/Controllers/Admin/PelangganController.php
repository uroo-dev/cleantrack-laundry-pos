<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pelanggan = Pelanggan::withCount('transaksis')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_pelanggan', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::whereHas('transaksis', function ($q) {
            $q->whereDate('created_at', '>=', now()->subDays(30));
        })->count();
        $rataOrder = $totalPelanggan > 0 ? round(Pelanggan::withCount('transaksis')->get()->avg('transaksis_count'), 1) : 0;
        $loyalitasTinggi = Pelanggan::withCount('transaksis')->having('transaksis_count', '>', 10)->count();

        return view('admin.pelanggan.index', compact(
            'pelanggan', 'search',
            'totalPelanggan', 'pelangganAktif', 'rataOrder', 'loyalitasTinggi'
        ));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20'],
            'email'   => ['nullable', 'email', 'max:255', 'unique:pelanggans,email'],
            'alamat'  => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $lastPelanggan = Pelanggan::withTrashed()
                ->where('kode_pelanggan', 'like', 'PLG-' . date('Ymd') . '-%')
                ->orderBy('kode_pelanggan', 'desc')
                ->first();

            $lastNumber = $lastPelanggan ? (int) substr($lastPelanggan->kode_pelanggan, -3) : 0;
            $validated['kode_pelanggan'] = 'PLG-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $validated['poin'] = 0;
            $validated['total_transaksi'] = 0;

            Pelanggan::create($validated);

            $redirect = $request->input('redirect', 'admin.pelanggan.index');

            return $redirect === 'transaksi'
                ? redirect()->route('admin.transaksi.create')->with('success', 'Pelanggan berhasil ditambahkan.')
                : redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage());
        }
    }

    public function edit(Pelanggan $pelanggan)
    {
        $pelanggan->loadCount('transaksis');
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20'],
            'email'   => ['nullable', 'email', 'max:255', 'unique:pelanggans,email,' . $pelanggan->id],
            'alamat'  => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $pelanggan->update($validated);

            return redirect()->route('admin.pelanggan.index')
                ->with('success', 'Pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui pelanggan: ' . $e->getMessage());
        }
    }

    public function destroy(Pelanggan $pelanggan)
    {
        try {
            $pelanggan->delete();

            return redirect()->route('admin.pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }
}
