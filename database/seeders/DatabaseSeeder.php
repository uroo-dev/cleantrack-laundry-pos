<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\DetailLaundry;
use App\Models\Tracking;
use App\Models\Pembayaran;
use App\Models\Setting;
use App\Models\Rating;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@laundry.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '08123456780',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@laundry.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08123456781',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@laundry.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '08123456782',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@laundry.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '08123456783',
            'is_active' => true,
        ]);

        $layananData = [
            ['nama' => 'Cuci + Setrika', 'deskripsi' => 'Layanan cuci lengkap dengan setrika', 'harga_perkg' => 7000, 'estimasi_hari' => 1],
            ['nama' => 'Cuci Kering', 'deskripsi' => 'Layanan cuci tanpa setrika', 'harga_perkg' => 5000, 'estimasi_hari' => 1],
            ['nama' => 'Setrika Aja', 'deskripsi' => 'Layanan setrika saja', 'harga_perkg' => 4000, 'estimasi_hari' => 1],
            ['nama' => 'Cuci + Setrika Express', 'deskripsi' => 'Layanan cuci express selesai setengah hari', 'harga_perkg' => 10000, 'estimasi_hari' => 0],
            ['nama' => 'Dry Clean', 'deskripsi' => 'Layanan dry clean untuk pakaian khusus', 'harga_perkg' => 15000, 'estimasi_hari' => 2],
        ];

        foreach ($layananData as $data) {
            Layanan::create($data);
        }

        $statuses = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];

        $today = date('Ymd');
        for ($i = 1; $i <= 20; $i++) {
            $pelanggan = Pelanggan::create([
                'kode_pelanggan' => 'PLG-' . $today . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'nama' => $faker->name(),
                'telepon' => $faker->phoneNumber(),
                'email' => $faker->email(),
                'password' => Hash::make('password'),
                'alamat' => $faker->address(),
                'poin' => $faker->numberBetween(0, 500),
                'total_transaksi' => $faker->numberBetween(0, 20),
            ]);
        }

        $layanans = Layanan::all();
        $pelanggans = Pelanggan::all();
        $users = User::all();
        $staffUsers = $users->where('role', 'staff');
        $staffUser = $staffUsers->first();

        for ($i = 1; $i <= 20; $i++) {
            $layanan = $layanans->random();
            $pelanggan = $pelanggans->random();
            $user = $staffUsers->random();
            $berat = $faker->randomFloat(2, 1, 10);
            $harga = $layanan->harga_perkg * $berat;
            $diskon = $faker->boolean(20) ? $faker->randomFloat(2, 0, $harga * 0.2) : 0;
            $total = $harga - $diskon;

            $status = $faker->randomElement($statuses);

            $estimasiSelesai = match ($layanan->estimasi_hari) {
                0 => now()->addHours(12),
                default => now()->addDays($layanan->estimasi_hari),
            };

            $tanggalAmbil = $status === 'diambil' ? $faker->dateTimeBetween('-2 days', 'now') : null;

            $transaksi = Transaksi::create([
                'kode_transaksi' => 'LDR-' . $today . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'pelanggan_id' => $pelanggan->id,
                'user_id' => $user->id,
                'layanan_id' => $layanan->id,
                'berat' => $berat,
                'harga' => $harga,
                'diskon' => $diskon,
                'total' => $total,
                'status' => $status,
                'catatan' => $faker->boolean(30) ? $faker->sentence() : null,
                'estimasi_selesai' => $estimasiSelesai,
                'tanggal_ambil' => $tanggalAmbil,
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now(),
            ]);

            $trackingStatuses = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai'];
            $trackingDescriptions = [
                'menunggu' => 'Pesanan diterima dan menunggu proses',
                'dicuci' => 'Pakaian sedang dicuci',
                'dikeringkan' => 'Pakaian sedang dikeringkan',
                'disetrika' => 'Pakaian sedang disetrika',
                'selesai' => 'Pakaian sudah siap diambil',
            ];

            $statusIndex = array_search($status, $statuses);
            $statusOrder = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];
            $currentIndex = array_search($status, $statusOrder);

            for ($j = 0; $j <= $currentIndex; $j++) {
                if ($j < 5) {
                    $timeOffset = ($j + 1) * $faker->numberBetween(30, 120);
                    Tracking::create([
                        'transaksi_id' => $transaksi->id,
                        'status' => $statusOrder[$j],
                        'keterangan' => $trackingDescriptions[$statusOrder[$j]],
                        'waktu' => (clone $transaksi->created_at)->addMinutes($timeOffset),
                        'created_at' => (clone $transaksi->created_at)->addMinutes($timeOffset),
                        'updated_at' => (clone $transaksi->created_at)->addMinutes($timeOffset),
                    ]);
                }
            }

            if ($status === 'diambil') {
                Tracking::create([
                    'transaksi_id' => $transaksi->id,
                    'status' => 'diambil',
                    'keterangan' => 'Pakaian sudah diambil oleh pelanggan',
                    'waktu' => $tanggalAmbil,
                    'created_at' => $tanggalAmbil,
                    'updated_at' => $tanggalAmbil,
                ]);
            }

            $pembayaranStatus = in_array($status, ['selesai', 'diambil']) ? 'lunas' : ($faker->boolean(30) ? 'cicil' : 'belum');
            $tanggalBayar = $pembayaranStatus === 'lunas' ? $faker->dateTimeBetween('-1 month', 'now') : null;

            Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'metode_pembayaran' => $faker->randomElement(['tunai', 'transfer', 'qris', 'debit']),
                'jumlah_bayar' => $pembayaranStatus === 'lunas' ? $total : ($pembayaranStatus === 'cicil' ? $faker->randomFloat(2, $total * 0.3, $total * 0.8) : 0),
                'kembalian' => 0,
                'status' => $pembayaranStatus,
                'tanggal_bayar' => $tanggalBayar,
            ]);

            $numItems = $faker->numberBetween(1, 5);
            for ($k = 0; $k < $numItems; $k++) {
                DetailLaundry::create([
                    'transaksi_id' => $transaksi->id,
                    'nama_barang' => $faker->randomElement([
                        'Kemeja', 'Kaos', 'Celana Panjang', 'Celana Pendek',
                        'Dress', 'Rok', 'Jaket', 'Sweater', 'Hoodie',
                        'Handuk', 'Seprai', 'Sarung Bantal', 'Selimut',
                        'Kaus Kaki', 'Jas', 'Blazer', 'Gaun', 'Batik',
                    ]) . ' ' . $faker->randomElement(['Putih', 'Hitam', 'Biru', 'Merah', 'Kuning', 'Hijau', 'Abu-abu', 'Coklat']),
                    'jumlah' => $faker->numberBetween(1, 3),
                    'catatan' => $faker->boolean(20) ? $faker->sentence(3) : null,
                ]);
            }

            if ($status === 'diambil' && $faker->boolean(50)) {
                Rating::create([
                    'transaksi_id' => $transaksi->id,
                    'rating' => $faker->numberBetween(3, 5),
                    'review' => $faker->boolean(70) ? $faker->sentence() : null,
                ]);
            }
        }

        $settings = [
            ['key' => 'nama_app', 'value' => 'LaundryKu'],
            ['key' => 'alamat', 'value' => 'Jl. Laundry No.1'],
            ['key' => 'telepon', 'value' => '08123456789'],
            ['key' => 'email', 'value' => 'info@laundryku.com'],
            ['key' => 'deskripsi', 'value' => 'LaundryKu - Solusi laundry cepat dan bersih untuk Anda'],
            ['key' => 'ongkos_kurir', 'value' => '5000'],
            ['key' => 'estimasi_hari', 'value' => '1'],
            ['key' => 'diskon_baru', 'value' => '10'],
            ['key' => 'batas_poin', 'value' => '100'],
            ['key' => 'konversi_poin', 'value' => '1000'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
