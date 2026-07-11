<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['transaksi_id', 'status', 'keterangan', 'waktu'])]
class Tracking extends Model
{
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }
}
