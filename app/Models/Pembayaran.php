<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['transaksi_id', 'metode_pembayaran', 'jumlah_bayar', 'kembalian', 'status', 'tanggal_bayar'])]
class Pembayaran extends Model
{
    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'kembalian' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
        ];
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }
}
