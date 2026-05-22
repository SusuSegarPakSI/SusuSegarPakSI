<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produks';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori',
        'satuan',
        'deskripsi',
        'harga_jual',
        'harga_pokok',
        'stok',
        'stok_minimum',
        'foto',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_jual'   => 'decimal:2',
            'harga_pokok'  => 'decimal:2',
            'is_active'    => 'boolean',
        ];
    }

    /**
     * Auto-generate kode_produk with DB lock to prevent race conditions.
     * Format: PRD-XXXXX
     */
    public static function generateKode(): string
    {
        return DB::transaction(function () {
            $max = DB::table('produks')
                ->lockForUpdate()
                ->max('id') ?? 0;
            return 'PRD-' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Get formatted harga_jual.
     */
    public function getHargaJualFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    /**
     * Get formatted harga_pokok.
     */
    public function getHargaPokokFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_pokok, 0, ',', '.');
    }

    /**
     * Relationship: Produk has many BOM entries.
     */
    public function bom(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BillOfMaterial::class, 'produk_id');
    }

    /**
     * Relationship: Produk has many production records.
     */
    public function produksis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Produksi::class, 'produk_output_id');
    }
}
