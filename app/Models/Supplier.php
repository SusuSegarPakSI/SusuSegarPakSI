<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'nama_kontak',
        'telepon',
        'alamat',
        'saldo_hutang',
        'saldo_awal_hutang',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'saldo_hutang'      => 'decimal:2',
            'saldo_awal_hutang' => 'decimal:2',
            'is_active'         => 'boolean',
        ];
    }

    /**
     * Auto-generate kode_supplier with DB lock to prevent race conditions.
     * Format: SUP-XXXXX
     */
    public static function generateKode(): string
    {
        return DB::transaction(function () {
            $max = DB::table('suppliers')
                ->lockForUpdate()
                ->max('id') ?? 0;
            return 'SUP-' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationship: Supplier has many BahanBaku.
     */
    public function bahanBakus(): HasMany
    {
        return $this->hasMany(BahanBaku::class, 'supplier_utama_id');
    }

    /**
     * Get formatted saldo_hutang.
     */
    public function getSaldoHutangFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo_hutang, 0, ',', '.');
    }
}
