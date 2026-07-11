<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['kode_transaksi', 'pelanggan_id', 'user_id', 'layanan_id', 'berat', 'harga', 'diskon', 'total', 'status', 'catatan', 'estimasi_selesai', 'tanggal_ambil'])]
class Transaksi extends Model
{
    protected function casts(): array
    {
        return [
            'estimasi_selesai' => 'datetime',
            'tanggal_ambil' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function detailLaundries(): HasMany
    {
        return $this->hasMany(DetailLaundry::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }
}
