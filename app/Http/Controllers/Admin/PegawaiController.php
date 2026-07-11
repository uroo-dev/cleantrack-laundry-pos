<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pegawais = User::where('role', 'staff')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pegawai.index', compact('pegawais', 'search'));
    }

    public function create()
    {
        return view('admin.pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        try {
            User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'phone'     => $validated['phone'],
                'role'      => 'staff',
                'is_active' => true,
            ]);

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan pegawai: ' . $e->getMessage());
        }
    }

    public function edit(User $pegawai)
    {
        if ($pegawai->role !== 'staff') {
            abort(404);
        }

        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, User $pegawai)
    {
        if ($pegawai->role !== 'staff') {
            abort(404);
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $pegawai->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        try {
            $data = [
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'is_active' => $validated['is_active'] ?? $pegawai->is_active,
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $pegawai->update($data);

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui pegawai: ' . $e->getMessage());
        }
    }

    public function destroy(User $pegawai)
    {
        if ($pegawai->role !== 'staff') {
            abort(404);
        }

        try {
            $pegawai->delete();

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pegawai: ' . $e->getMessage());
        }
    }
}
