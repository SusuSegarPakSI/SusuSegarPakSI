<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustments';

    protected $fillable = [
        'item_type',
        'item_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'alasan',
        'tanggal',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'stok_sistem' => 'integer',
            'stok_fisik'  => 'integer',
            'selisih'     => 'integer',
            'tanggal'     => 'date',
        ];
    }

    /**
     * Relationship: StockAdjustment belongs to Creator User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the adjusted item instance (Produk or BahanBaku).
     */
    public function getItemAttribute()
    {
        if ($this->item_type === 'produk') {
            return Produk::find($this->item_id);
        } elseif ($this->item_type === 'bahan_baku') {
            return BahanBaku::find($this->item_id);
        }
        return null;
    }
}
