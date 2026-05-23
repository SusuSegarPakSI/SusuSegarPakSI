<?php

namespace App\Services;

use App\Models\Pembelian;
use App\Models\PembayaranHutang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\PenjualanRetur;
use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\BebanLain;
use App\Models\Pengaturan;
use App\Models\ProduksiBiaya;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    /**
     * Hitung Laba Rugi untuk periode tertentu.
     *
     * @param Carbon $dari
     * @param Carbon $sampai
     * @return array
     */
    public function hitungLabaRugi(Carbon $dari, Carbon $sampai): array
    {
        $cacheKey = 'laporan_laba_rugi_' . $dari->format('Ymd') . '_' . $sampai->format('Ymd');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dari, $sampai) {
            // 1. Omzet = SUM penjualan.grand_total where status != 'Retur Penuh'
            $omzet = Penjualan::where('status', '!=', 'Retur Penuh')
                ->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
                ->sum('grand_total');

            // 2. Total Retur Nominal
            $totalRetur = PenjualanRetur::whereHas('penjualan', function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()]);
            })->sum('total_nilai_retur');

            $penjualanBersih = $omzet - $totalRetur;

            // 3. HPP Terjual = SUM(penjualan_detail.kuantitas * penjualan_detail.hpp_satuan)
            //    gunakan hpp_satuan yang disimpan saat transaksi, BUKAN harga_pokok live
            $hppTerjual = PenjualanDetail::whereHas('penjualan', function ($q) use ($dari, $sampai) {
                $q->where('status', '!=', 'Retur Penuh')
                  ->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()]);
            })->sum(DB::raw('kuantitas * hpp_satuan'));

            // 4. Laba Kotor
            $labaKotor = $penjualanBersih - $hppTerjual;

            // 5. Beban Operasional
            // 5a. Biaya Produksi (overhead + tenaga_kerja)
            $biayaProduksi = ProduksiBiaya::whereHas('produksi', function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_mulai', [$dari->toDateString(), $sampai->toDateString()]);
            })->whereIn('jenis_biaya', ['overhead', 'tenaga_kerja'])->sum('nominal');

            // 5b. Beban Lain-lain (sewa, listrik, dll - input manual Admin)
            $bebanLainLain = BebanLain::whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
                ->sum('nominal');

            // 5c. Breakdown per jenis beban
            $breakdownBeban = [];

            if ($biayaProduksi > 0) {
                $overheadProduksi = ProduksiBiaya::whereHas('produksi', function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal_mulai', [$dari->toDateString(), $sampai->toDateString()]);
                })->where('jenis_biaya', 'overhead')->sum('nominal');

                $tenagaKerja = ProduksiBiaya::whereHas('produksi', function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal_mulai', [$dari->toDateString(), $sampai->toDateString()]);
                })->where('jenis_biaya', 'tenaga_kerja')->sum('nominal');

                if ($overheadProduksi > 0) {
                    $breakdownBeban[] = ['nama' => 'Overhead Produksi', 'nominal' => $overheadProduksi];
                }
                if ($tenagaKerja > 0) {
                    $breakdownBeban[] = ['nama' => 'Tenaga Kerja Produksi', 'nominal' => $tenagaKerja];
                }
            }

            // Get beban lain-lain breakdown items
            $bebanLainItems = BebanLain::whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
                ->select('keterangan', DB::raw('SUM(nominal) as nominal'))
                ->groupBy('keterangan')
                ->orderBy('nominal', 'desc')
                ->get();

            foreach ($bebanLainItems as $bl) {
                $breakdownBeban[] = ['nama' => $bl->keterangan, 'nominal' => $bl->nominal];
            }

            $totalBebanOperasional = $biayaProduksi + $bebanLainLain;

            // 6. Laba Bersih
            $labaBersih = $labaKotor - $totalBebanOperasional;

            // 7. Breakdown per bulan jika range > 1 bulan
            $breakdownBulanan = [];
            $diffMonths = $dari->diffInMonths($sampai);
            if ($diffMonths >= 1) {
                $current = $dari->copy()->startOfMonth();
                while ($current->lte($sampai)) {
                    $bulanAwal = $current->copy()->startOfMonth();
                    $bulanAkhir = $current->copy()->endOfMonth();
                    if ($bulanAkhir->gt($sampai)) $bulanAkhir = $sampai->copy();
                    if ($bulanAwal->lt($dari)) $bulanAwal = $dari->copy();

                    $omzetBulan = Penjualan::where('status', '!=', 'Retur Penuh')
                        ->whereBetween('tanggal', [$bulanAwal->toDateString(), $bulanAkhir->toDateString()])
                        ->sum('grand_total');

                    $hppBulan = PenjualanDetail::whereHas('penjualan', function ($q) use ($bulanAwal, $bulanAkhir) {
                        $q->where('status', '!=', 'Retur Penuh')
                          ->whereBetween('tanggal', [$bulanAwal->toDateString(), $bulanAkhir->toDateString()]);
                    })->sum(DB::raw('kuantitas * hpp_satuan'));

                    $bebanBulan = ProduksiBiaya::whereHas('produksi', function ($q) use ($bulanAwal, $bulanAkhir) {
                        $q->whereBetween('tanggal_mulai', [$bulanAwal->toDateString(), $bulanAkhir->toDateString()]);
                    })->whereIn('jenis_biaya', ['overhead', 'tenaga_kerja'])->sum('nominal')
                    + BebanLain::whereBetween('tanggal', [$bulanAwal->toDateString(), $bulanAkhir->toDateString()])->sum('nominal');

                    $breakdownBulanan[] = [
                        'bulan' => $current->translatedFormat('M Y'),
                        'omzet' => $omzetBulan,
                        'hpp'   => $hppBulan,
                        'laba_kotor' => $omzetBulan - $hppBulan,
                        'beban' => $bebanBulan,
                        'laba_bersih' => ($omzetBulan - $hppBulan) - $bebanBulan,
                    ];

                    $current->addMonth();
                }
            }

            return [
                'omzet'                 => $omzet,
                'total_retur'           => $totalRetur,
                'penjualan_bersih'      => $penjualanBersih,
                'hpp_terjual'           => $hppTerjual,
                'laba_kotor'            => $labaKotor,
                'biaya_produksi'        => $biayaProduksi,
                'beban_lain_lain'       => $bebanLainLain,
                'total_beban_operasional' => $totalBebanOperasional,
                'breakdown_beban'       => $breakdownBeban,
                'laba_bersih'           => $labaBersih,
                'breakdown_bulanan'     => $breakdownBulanan,
            ];
        });
    }

    /**
     * Hitung Arus Kas untuk periode tertentu.
     *
     * @param Carbon $dari
     * @param Carbon $sampai
     * @return array
     */
    public function hitungArusKas(Carbon $dari, Carbon $sampai): array
    {
        $cacheKey = 'laporan_arus_kas_' . $dari->format('Ymd') . '_' . $sampai->format('Ymd');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dari, $sampai) {
            // Kas Masuk: SUM penjualan lunas dalam range
            $kasMasukPenjualan = Penjualan::where('status', '!=', 'Retur Penuh')
                ->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
                ->sum('grand_total');

            // Kas Keluar: pembelian lunas + pembayaran hutang
            $kasKeluarPembelianLunas = Pembelian::where('status_bayar', 'Lunas')
                ->whereBetween('tanggal_faktur', [$dari->toDateString(), $sampai->toDateString()])
                ->sum('total');

            $kasKeluarPembayaranHutang = PembayaranHutang::whereBetween('tanggal_bayar', [$dari->toDateString(), $sampai->toDateString()])
                ->sum('jumlah_bayar');

            // Kas Keluar Beban Lain
            $kasKeluarBebanLain = BebanLain::whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
                ->sum('nominal');

            // Biaya Produksi (overhead + tenaga_kerja) as cash outflow
            $kasKeluarBiayaProduksi = ProduksiBiaya::whereHas('produksi', function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_mulai', [$dari->toDateString(), $sampai->toDateString()]);
            })->whereIn('jenis_biaya', ['overhead', 'tenaga_kerja'])->sum('nominal');

            $totalKasMasuk = $kasMasukPenjualan;
            $totalKasKeluar = $kasKeluarPembelianLunas + $kasKeluarPembayaranHutang + $kasKeluarBebanLain + $kasKeluarBiayaProduksi;

            // Saldo Awal: dari tabel pengaturan
            $saldoAwal = (float) Pengaturan::getVal('saldo_kas_awal', 0);

            // Saldo Akhir
            $netCashFlow = $totalKasMasuk - $totalKasKeluar;
            $saldoAkhir = $saldoAwal + $netCashFlow;

            return [
                'saldo_awal'                 => $saldoAwal,
                'kas_masuk_penjualan'        => $kasMasukPenjualan,
                'total_kas_masuk'            => $totalKasMasuk,
                'kas_keluar_pembelian_lunas'   => $kasKeluarPembelianLunas,
                'kas_keluar_pembayaran_hutang' => $kasKeluarPembayaranHutang,
                'kas_keluar_beban_lain'        => $kasKeluarBebanLain,
                'kas_keluar_biaya_produksi'    => $kasKeluarBiayaProduksi,
                'total_kas_keluar'             => $totalKasKeluar,
                'net_cash_flow'              => $netCashFlow,
                'saldo_akhir'                => $saldoAkhir,
            ];
        });
    }

    /**
     * Hitung Neraca sampai dengan tanggal tertentu.
     *
     * @param Carbon $sampai
     * @return array
     */
    public function hitungNeraca(Carbon $sampai): array
    {
        $cacheKey = 'laporan_neraca_' . $sampai->format('Ymd');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($sampai) {
            // Hitung arus kas s.d. tanggal untuk mendapatkan saldo kas akhir
            $saldoAwal = (float) Pengaturan::getVal('saldo_kas_awal', 0);

            $kasMasuk = Penjualan::where('status', '!=', 'Retur Penuh')
                ->where('tanggal', '<=', $sampai->toDateString())
                ->sum('grand_total');

            $kasKeluarPembelian = Pembelian::where('status_bayar', 'Lunas')
                ->where('tanggal_faktur', '<=', $sampai->toDateString())
                ->sum('total');

            $kasKeluarPembayaran = PembayaranHutang::where('tanggal_bayar', '<=', $sampai->toDateString())
                ->sum('jumlah_bayar');

            $kasKeluarBeban = BebanLain::where('tanggal', '<=', $sampai->toDateString())
                ->sum('nominal');

            $kasKeluarBiayaProd = ProduksiBiaya::whereHas('produksi', function ($q) use ($sampai) {
                $q->where('tanggal_mulai', '<=', $sampai->toDateString());
            })->whereIn('jenis_biaya', ['overhead', 'tenaga_kerja'])->sum('nominal');

            $kas = $saldoAwal + $kasMasuk - $kasKeluarPembelian - $kasKeluarPembayaran - $kasKeluarBeban - $kasKeluarBiayaProd;

            // Piutang
            $piutang = Customer::sum('saldo_piutang');

            // Persediaan Produk
            $persediaanProduk = Produk::where('is_active', true)
                ->select(DB::raw('SUM(stok * harga_pokok) as total'))
                ->value('total') ?? 0;

            // Persediaan Bahan Baku
            $persediaanBahan = BahanBaku::where('is_active', true)
                ->select(DB::raw('SUM(stok * harga_rata_rata) as total'))
                ->value('total') ?? 0;

            $totalAset = $kas + $piutang + $persediaanProduk + $persediaanBahan;

            // KEWAJIBAN
            $hutangUsaha = Supplier::sum('saldo_hutang');
            $totalKewajiban = $hutangUsaha;

            // EKUITAS
            $ekuitas = $totalAset - $totalKewajiban;
            $totalKewajibanEkuitas = $totalKewajiban + $ekuitas;

            return [
                'aset' => [
                    'kas'                => $kas,
                    'piutang'            => $piutang,
                    'persediaan_produk'  => $persediaanProduk,
                    'persediaan_bahan'   => $persediaanBahan,
                    'total_aset'         => $totalAset,
                ],
                'kewajiban' => [
                    'hutang_usaha'       => $hutangUsaha,
                    'total_kewajiban'    => $totalKewajiban,
                ],
                'ekuitas' => [
                    'modal_ekuitas'      => $ekuitas,
                ],
                'total_kewajiban_ekuitas' => $totalKewajibanEkuitas,
                'balanced' => abs($totalAset - $totalKewajibanEkuitas) < 0.01,
            ];
        });
    }
}
