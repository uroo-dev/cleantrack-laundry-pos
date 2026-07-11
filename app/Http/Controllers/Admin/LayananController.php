<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::latest()->paginate(10);

        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'deskripsi'    => ['nullable', 'string', 'max:1000'],
            'harga_perkg'  => ['required', 'numeric', 'min:0'],
            'estimasi_hari' => ['required', 'integer', 'min:1'],
            'is_active'    => ['boolean'],
        ]);

        try {
            Layanan::create($validated);

            return redirect()->route('admin.layanan.index')
                ->with('success', 'Layanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan layanan: ' . $e->getMessage());
        }
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'deskripsi'    => ['nullable', 'string', 'max:1000'],
            'harga_perkg'  => ['required', 'numeric', 'min:0'],
            'estimasi_hari' => ['required', 'integer', 'min:1'],
            'is_active'    => ['boolean'],
        ]);

        try {
            $layanan->update($validated);

            return redirect()->route('admin.layanan.index')
                ->with('success', 'Layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui layanan: ' . $e->getMessage());
        }
    }

    public function destroy(Layanan $layanan)
    {
        try {
            $layanan->delete();

            return redirect()->route('admin.layanan.index')
                ->with('success', 'Layanan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus layanan: ' . $e->getMessage());
        }
    }
}
