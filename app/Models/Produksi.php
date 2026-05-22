<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksi';

    protected $fillable = [
        'nomor_produksi',
        'tanggal_mulai',
        'tanggal_selesai',
        'nama_batch',
        'produk_output_id',
        'jumlah_batch',
        'target_output',
        'actual_output',
        'status',
        'hpp_per_unit',
        'catatan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'jumlah_batch'    => 'integer',
            'target_output'   => 'integer',
            'actual_output'   => 'integer',
            'hpp_per_unit'    => 'decimal:2',
        ];
    }

    /**
     * Generate Nomor Produksi (PRD-YYYYMMDD-XXXX)
     * atomic with DB lock
     */
    public static function generateNomorProduksi(string $date): string
    {
        $formattedDate = date('Ymd', strtotime($date));
        
        return DB::transaction(function () use ($date, $formattedDate) {
            $count = DB::table('produksi')
                ->whereDate('tanggal_mulai', $date)
                ->lockForUpdate()
                ->count();
                
            return 'PRD-' . $formattedDate . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationship: Produksi yields a Produk.
     */
    public function produkOutput(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_output_id');
    }

    /**
     * Relationship: Produksi is created by a User (Admin).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: Produksi has many biaya details.
     */
    public function biayas(): HasMany
    {
        return $this->hasMany(ProduksiBiaya::class, 'produksi_id');
    }

    /**
     * Relationship: Produksi has many bahan pakai details.
     */
    public function bahanPakais(): HasMany
    {
        return $this->hasMany(ProduksiBahanPakai::class, 'produksi_id');
    }

    // Helper: Formatted values
    public function getHppPerUnitFormattedAttribute(): string
    {
        return $this->hpp_per_unit ? 'Rp ' . number_format($this->hpp_per_unit, 0, ',', '.') : '-';
    }
}
