<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $table = 'bill_of_materials';

    protected $fillable = [
        'produk_id',
        'bahan_baku_id',
        'kuantitas_per_batch',
        'satuan',
    ];

    protected function casts(): array
    {
        return [
            'kuantitas_per_batch' => 'decimal:2',
        ];
    }

    /**
     * Relationship: BOM belongs to a Produk.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relationship: BOM belongs to a BahanBaku.
     */
    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
