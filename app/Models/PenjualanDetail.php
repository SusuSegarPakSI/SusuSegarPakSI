<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_details';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'harga_satuan',
        'hpp_satuan',
        'kuantitas',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'hpp_satuan'   => 'decimal:2',
            'subtotal'     => 'decimal:2',
        ];
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
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

    public function getHppSatuanFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->hpp_satuan, 0, ',', '.');
    }

    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
