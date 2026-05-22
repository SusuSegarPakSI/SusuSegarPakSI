<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanReturDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_retur_details';

    protected $fillable = [
        'retur_id',
        'produk_id',
        'kuantitas_retur',
        'harga_satuan',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'subtotal'     => 'decimal:2',
        ];
    }

    public function retur()
    {
        return $this->belongsTo(PenjualanRetur::class, 'retur_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id')->withTrashed();
    }

    // Formatted monetary attributes
    public function getHargaSatuanFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
