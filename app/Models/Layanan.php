<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'deskripsi', 'harga_perkg', 'estimasi_hari', 'is_active'])]
class Layanan extends Model
{
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
