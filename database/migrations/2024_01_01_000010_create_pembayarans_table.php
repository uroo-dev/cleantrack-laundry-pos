<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->string('metode_pembayaran', 50)->default('tunai');
            $table->decimal('jumlah_bayar', 10, 2);
            $table->decimal('kembalian', 10, 2)->default(0);
            $table->enum('status', ['lunas', 'belum', 'cicil'])->default('belum');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
