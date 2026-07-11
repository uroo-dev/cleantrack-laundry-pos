<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('admin.pengaturan.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_app'         => ['required', 'string', 'max:255'],
            'alamat'           => ['nullable', 'string', 'max:500'],
            'telepon'          => ['nullable', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:255'],
            'whatsapp'         => ['nullable', 'string', 'max:20'],
            'deskripsi'        => ['nullable', 'string', 'max:1000'],
            'hero_title'       => ['nullable', 'string', 'max:255'],
            'hero_subtitle'    => ['nullable', 'string', 'max:255'],
            'tentang'          => ['nullable', 'string', 'max:2000'],
        ]);

        $keyMapping = [
            'nama_app' => 'nama_app',
            'alamat' => 'alamat',
            'telepon' => 'telepon',
            'email' => 'email',
            'whatsapp' => 'whatsapp',
            'deskripsi' => 'deskripsi',
            'hero_title' => 'hero_title',
            'hero_subtitle' => 'hero_subtitle',
            'tentang' => 'tentang',
        ];

        try {
            foreach ($validated as $requestKey => $value) {
                $dbKey = $keyMapping[$requestKey] ?? $requestKey;
                Setting::updateOrCreate(
                    ['key' => $dbKey],
                    ['value' => $value]
                );
            }

            return redirect()->route('admin.pengaturan.index')
                ->with('success', 'Pengaturan berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            Transaksi::query()->delete();
            Pelanggan::query()->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function export()
    {
        $data = [
            'pelanggan' => Pelanggan::all(),
            'transaksi' => Transaksi::with(['pelanggan', 'layanan'])->get(),
            'settings' => Setting::all()->pluck('value', 'key'),
        ];

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="laundry-export-' . date('Y-m-d') . '.json"',
        ]);
    }
}
