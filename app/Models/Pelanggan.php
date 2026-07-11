<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode_pelanggan', 'nama', 'telepon', 'email', 'password', 'alamat', 'poin', 'total_transaksi'])]
#[Hidden(['password'])]
class Pelanggan extends Model
{
    protected function casts(): array
    {
        return [
            'poin' => 'integer',
            'total_transaksi' => 'integer',
        ];
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function getTotalSpendingAttribute(): int
    {
        return (int) $this->transaksis()->sum('total');
    }

    public function getTransaksiTerakhirAttribute(): ?string
    {
        $last = $this->transaksis()->latest()->first();
        return $last?->created_at;
    }
}
