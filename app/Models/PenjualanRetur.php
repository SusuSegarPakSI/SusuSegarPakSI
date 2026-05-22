<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanRetur extends Model
{
    use HasFactory;

    protected $table = 'penjualan_returs';

    protected $fillable = [
        'penjualan_id',
        'tanggal_retur',
        'alasan',
        'total_nilai_retur',
        'kembalikan_stok',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_retur'     => 'date',
            'total_nilai_retur' => 'decimal:2',
            'kembalikan_stok'   => 'boolean',
        ];
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function details()
    {
        return $this->hasMany(PenjualanReturDetail::class, 'retur_id');
    }

    // Formatted attributes
    public function getTotalNilaiReturFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_nilai_retur, 0, ',', '.');
    }
}
