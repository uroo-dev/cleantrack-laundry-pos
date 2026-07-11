<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
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
            'harga_perkg'      => ['nullable', 'numeric', 'min:0'],
        ]);

        $keyMapping = [
            'nama_app' => 'nama_app',
            'alamat' => 'alamat',
            'telepon' => 'telepon',
            'email' => 'email',
            'harga_perkg' => 'harga_perkg',
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
}
