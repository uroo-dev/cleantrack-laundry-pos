<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status_pembayaran', 20)->default('belum')->after('status');
            $table->decimal('dp', 10, 2)->default(0)->after('diskon');
            $table->decimal('pajak', 10, 2)->default(0)->after('dp');
            $table->string('outlet', 100)->nullable()->after('catatan');
            $table->text('qr_code')->nullable()->after('outlet');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'dp', 'pajak', 'outlet', 'qr_code']);
        });
    }
};
