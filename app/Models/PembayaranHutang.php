<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranHutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_hutangs';

    protected $fillable = [
        'pembelian_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'jumlah_bayar'  => 'decimal:2',
        ];
    }

    /**
     * Relationship: PembayaranHutang belongs to Pembelian.
     */
    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    /**
     * Get formatted jumlah_bayar.
     */
    public function getJumlahBayarFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }
}
