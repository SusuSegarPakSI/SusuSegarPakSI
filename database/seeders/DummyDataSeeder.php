<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\Customer;
use App\Models\BillOfMaterial;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\PembayaranHutang;
use App\Models\Produksi;
use App\Models\ProduksiBiaya;
use App\Models\ProduksiBahanPakai;
use App\Models\StockAdjustment;
use App\Models\BebanLain;
use App\Models\Pengaturan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Clean out existing data
        User::where('email', '!=', 'manajer@sususegarpaksi.com')
            ->where('email', '!=', 'admin@sususegarpaksi.com')
            ->delete();

        Supplier::truncate();
        BahanBaku::truncate();
        Produk::truncate();
        Customer::truncate();
        BillOfMaterial::truncate();
        Penjualan::truncate();
        PenjualanDetail::truncate();
        Pembelian::truncate();
        PembelianDetail::truncate();
        PembayaranHutang::truncate();
        Produksi::truncate();
        ProduksiBiaya::truncate();
        ProduksiBahanPakai::truncate();
        StockAdjustment::truncate();
        BebanLain::truncate();
        Pengaturan::truncate();

        Schema::enableForeignKeyConstraints();

        // 2. Load and seed users
        $users = require database_path('data/dummy/users.php');
        foreach ($users as $u) {
            User::create($u);
        }

        // 3. Load and seed suppliers
        $suppliers = require database_path('data/dummy/suppliers.php');
        foreach ($suppliers as $s) {
            Supplier::create($s);
        }

        // 4. Load and seed bahan bakus
        $bahanBakus = require database_path('data/dummy/bahan_bakus.php');
        foreach ($bahanBakus as $bb) {
            BahanBaku::create($bb);
        }

        // 5. Load and seed produks
        $produks = require database_path('data/dummy/produks.php');
        foreach ($produks as $p) {
            Produk::create($p);
        }

        // 6. Load and seed customers
        $customers = require database_path('data/dummy/customers.php');
        foreach ($customers as $c) {
            Customer::create($c);
        }

        // 7. Load and seed bill of materials
        $boms = require database_path('data/dummy/bill_of_materials.php');
        foreach ($boms as $bom) {
            BillOfMaterial::create($bom);
        }

        // 8. Load and seed penjualans
        $penjualans = require database_path('data/dummy/penjualans.php');
        foreach ($penjualans as $pj) {
            $details = $pj['details'];
            unset($pj['details']);
            
            $penjualan = Penjualan::create($pj);
            foreach ($details as $d) {
                $d['penjualan_id'] = $penjualan->id;
                PenjualanDetail::create($d);
            }
        }

        // 9. Load and seed pembelians
        $pembelians = require database_path('data/dummy/pembelians.php');
        foreach ($pembelians as $pb) {
            $details = $pb['details'];
            $pembayarans = $pb['pembayaran'];
            unset($pb['details'], $pb['pembayaran']);

            $pembelian = Pembelian::create($pb);
            foreach ($details as $d) {
                $d['pembelian_id'] = $pembelian->id;
                PembelianDetail::create($d);
            }
            foreach ($pembayarans as $p) {
                $p['pembelian_id'] = $pembelian->id;
                PembayaranHutang::create($p);
            }
        }

        // 10. Load and seed produksis
        $produksis = require database_path('data/dummy/produksis.php');
        foreach ($produksis as $pr) {
            $biayas = $pr['biaya'];
            $bahanPakais = $pr['bahan_pakai'];
            unset($pr['biaya'], $pr['bahan_pakai']);

            $produksi = Produksi::create($pr);
            foreach ($biayas as $b) {
                $b['produksi_id'] = $produksi->id;
                ProduksiBiaya::create($b);
            }
            foreach ($bahanPakais as $bp) {
                $bp['produksi_id'] = $produksi->id;
                ProduksiBahanPakai::create($bp);
            }
        }

        // 11. Load and seed stock adjustments
        $adjustments = require database_path('data/dummy/stock_adjustments.php');
        foreach ($adjustments as $adj) {
            StockAdjustment::create($adj);
        }

        // 12. Load and seed beban lains
        $bebanLains = require database_path('data/dummy/beban_lains.php');
        foreach ($bebanLains as $bl) {
            BebanLain::create($bl);
        }

        // 13. Load and seed pengaturans
        $pengaturans = require database_path('data/dummy/pengaturans.php');
        foreach ($pengaturans as $pg) {
            Pengaturan::create($pg);
        }
    }
}
