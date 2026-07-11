<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telepon'  => ['required', 'string', 'max:20'],
            'alamat'   => ['required', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone'    => $validated['telepon'],
                'role'     => 'customer',
                'is_active' => true,
            ]);

            $lastPelanggan = Pelanggan::withTrashed()
                ->where('kode_pelanggan', 'like', 'PLG-' . date('Ymd') . '-%')
                ->orderBy('kode_pelanggan', 'desc')
                ->first();

            $lastNumber = $lastPelanggan ? (int) substr($lastPelanggan->kode_pelanggan, -3) : 0;
            $kodePelanggan = 'PLG-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            Pelanggan::create([
                'kode_pelanggan'  => $kodePelanggan,
                'nama'            => $validated['name'],
                'telepon'         => $validated['telepon'],
                'email'           => $validated['email'],
                'alamat'          => $validated['alamat'],
                'poin'            => 0,
                'total_transaksi' => 0,
            ]);

            DB::commit();

            return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }
}
