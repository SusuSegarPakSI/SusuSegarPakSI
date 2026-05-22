<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduksiBiaya extends Model
{
    use HasFactory;

    protected $table = 'produksi_biaya';

    protected $fillable = [
        'produksi_id',
        'jenis_biaya',
        'keterangan',
        'nominal',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
        ];
    }

    /**
     * Relationship: Cost belongs to a Production.
     */
    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    // Helper: Formatted Nominal
    public function getNominalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}
