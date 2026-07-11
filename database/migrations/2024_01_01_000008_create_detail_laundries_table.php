<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_laundries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->string('nama_barang', 200);
            $table->integer('jumlah')->default(1);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_laundries');
    }
};
