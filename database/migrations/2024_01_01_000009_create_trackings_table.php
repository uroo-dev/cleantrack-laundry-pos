<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->string('status', 50);
            $table->text('keterangan')->nullable();
            $table->dateTime('waktu');
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
