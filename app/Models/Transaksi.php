<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'kode_transaksi', 'pelanggan_id', 'user_id', 'layanan_id',
    'berat', 'harga', 'diskon', 'dp', 'pajak', 'total',
    'status', 'status_pembayaran', 'catatan', 'outlet', 'qr_code',
    'estimasi_selesai', 'tanggal_ambil'
])]
class Transaksi extends Model
{
    protected function casts(): array
    {
        return [
            'estimasi_selesai' => 'datetime',
            'tanggal_ambil' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'berat' => 'decimal:2',
            'harga' => 'decimal:2',
            'diskon' => 'decimal:2',
            'dp' => 'decimal:2',
            'pajak' => 'decimal:2',
            'total' => 'decimal:2',
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
        return $this->hasMany(DetailLaundry::class, 'transaksi_id');
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

    public function getInvoiceAttribute(): string
    {
        return $this->kode_transaksi ?? 'INV-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }
}
