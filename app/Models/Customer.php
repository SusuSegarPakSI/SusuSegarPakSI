<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'kode_pelanggan',
        'nama',
        'tipe',
        'telepon',
        'email',
        'alamat',
        'catatan',
        'saldo_piutang',
        'saldo_awal_piutang',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'saldo_piutang'      => 'decimal:2',
            'saldo_awal_piutang' => 'decimal:2',
            'is_active'          => 'boolean',
        ];
    }

    /**
     * Use 'pelanggan' as the route model binding key name for URLs.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Auto-generate kode_pelanggan with DB lock to prevent race conditions.
     * Format: CST-XXXXX
     */
    public static function generateKode(): string
    {
        return DB::transaction(function () {
            $max = DB::table('customers')
                ->lockForUpdate()
                ->max('id') ?? 0;
            return 'CST-' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Get formatted saldo_piutang.
     */
    public function getSaldoPiutangFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo_piutang, 0, ',', '.');
    }
}
