<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduksiBahanPakai extends Model
{
    use HasFactory;

    protected $table = 'produksi_bahan_pakai';

    protected $fillable = [
        'produksi_id',
        'bahan_baku_id',
        'kuantitas_pakai',
        'harga_satuan_saat_itu',
    ];

    protected function casts(): array
    {
        return [
            'kuantitas_pakai'         => 'decimal:2',
            'harga_satuan_saat_itu'   => 'decimal:2',
        ];
    }

    /**
     * Relationship: Bahan Pakai belongs to a Production.
     */
    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    /**
     * Relationship: Bahan Pakai references a BahanBaku.
     */
    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Helper: Formatted values
    public function getHargaSatuanSaatItuFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_satuan_saat_itu, 0, ',', '.');
    }

    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->kuantitas_pakai * $this->harga_satuan_saat_itu, 0, ',', '.');
    }
}
