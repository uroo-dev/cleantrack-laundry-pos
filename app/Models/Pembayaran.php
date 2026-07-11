<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['transaksi_id', 'metode_pembayaran', 'jumlah_bayar', 'kembalian', 'status', 'tanggal_bayar'])]
class Pembayaran extends Model
{
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }
}
