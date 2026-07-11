<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelanggan', 50)->unique();
            $table->string('nama', 100);
            $table->string('telepon', 20);
            $table->string('email', 100)->nullable();
            $table->string('password', 255)->nullable();
            $table->text('alamat')->nullable();
            $table->integer('poin')->default(0);
            $table->integer('total_transaksi')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
