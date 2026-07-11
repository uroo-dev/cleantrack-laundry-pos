<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->unsignedBigInteger('pelanggan_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('layanan_id');
            $table->decimal('berat', 10, 2);
            $table->decimal('harga', 10, 2);
            $table->decimal('diskon', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->dateTime('estimasi_selesai')->nullable();
            $table->dateTime('tanggal_ambil')->nullable();
            $table->timestamps();

            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('layanan_id')->references('id')->on('layanans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
