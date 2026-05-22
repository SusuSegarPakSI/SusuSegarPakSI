<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class BahanBaku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bahan_bakus';

    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'kategori',
        'satuan',
        'stok',
        'stok_minimum',
        'harga_rata_rata',
        'supplier_utama_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_rata_rata' => 'decimal:2',
            'is_active'       => 'boolean',
        ];
    }

    /**
     * Auto-generate kode_bahan with DB lock to prevent race conditions.
     * Format: BHN-XXXXX
     */
    public static function generateKode(): string
    {
        return DB::transaction(function () {
            $max = DB::table('bahan_bakus')
                ->lockForUpdate()
                ->max('id') ?? 0;
            return 'BHN-' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationship: BahanBaku belongs to Supplier.
     */
    public function supplierUtama(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_utama_id');
    }

    /**
     * Get formatted harga_rata_rata.
     */
    public function getHargaRataRataFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_rata_rata, 0, ',', '.');
    }

    /**
     * Relationship: BahanBaku has many BOM entries.
     */
    public function bom(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BillOfMaterial::class, 'bahan_baku_id');
    }

    /**
     * Relationship: BahanBaku is used in many production runs.
     */
    public function bahanPakais(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProduksiBahanPakai::class, 'bahan_baku_id');
    }
}
