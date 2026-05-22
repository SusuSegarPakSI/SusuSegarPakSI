<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';

    protected $fillable = [
        'nomor_transaksi',
        'tanggal',
        'customer_id',
        'subtotal',
        'diskon_nominal',
        'grand_total',
        'metode_bayar',
        'jumlah_bayar',
        'kembalian',
        'status',
        'catatan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'         => 'date',
            'subtotal'        => 'decimal:2',
            'diskon_nominal'  => 'decimal:2',
            'grand_total'     => 'decimal:2',
            'jumlah_bayar'    => 'decimal:2',
            'kembalian'       => 'decimal:2',
        ];
    }

    /**
     * Generate Nomor Transaksi (INV-YYYYMMDD-XXXX)
     * atomic with DB lock
     */
    public static function generateNomorTransaksi(string $date): string
    {
        $formattedDate = date('Ymd', strtotime($date));
        
        return DB::transaction(function () use ($date, $formattedDate) {
            $count = DB::table('penjualans')
                ->where('tanggal', $date)
                ->lockForUpdate()
                ->count();
                
            return 'INV-' . $formattedDate . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id');
    }

    public function returs()
    {
        return $this->hasMany(PenjualanRetur::class, 'penjualan_id');
    }

    // Formatted monetary attributes
    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getDiskonNominalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->diskon_nominal, 0, ',', '.');
    }

    public function getGrandTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public function getJumlahBayarFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    public function getKembalianFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->kembalian, 0, ',', '.');
    }
}
