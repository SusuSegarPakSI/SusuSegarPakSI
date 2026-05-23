<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    protected $fillable = [
        'nomor_faktur',
        'nomor_po',
        'supplier_id',
        'tanggal_faktur',
        'tanggal_jatuh_tempo',
        'subtotal',
        'total',
        'status_bayar',
        'catatan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_faktur'      => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'subtotal'            => 'decimal:2',
            'total'               => 'decimal:2',
        ];
    }

    /**
     * Relationship: Pembelian belongs to Supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relationship: Pembelian belongs to Creator User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: Pembelian has many details.
     */
    public function details(): HasMany
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id');
    }

    /**
     * Relationship: Pembelian has many payments (pembayaran_hutang).
     */
    public function pembayaranHutangs(): HasMany
    {
        return $this->hasMany(PembayaranHutang::class, 'pembelian_id');
    }

    /**
     * Get formatted total.
     */
    public function getTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Get formatted subtotal.
     */
    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
