<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'deskripsi', 'harga_perkg', 'estimasi_hari', 'is_active'])]
class Layanan extends Model
{
    protected function casts(): array
    {
        return [
            'harga_perkg' => 'decimal:2',
            'estimasi_hari' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
