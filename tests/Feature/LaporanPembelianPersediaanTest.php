<?php

namespace Tests\Feature;

use App\Models\BahanBaku;
use App\Models\BebanLain;
use App\Models\Pembelian;
use App\Models\PembayaranHutang;
use App\Models\Pengaturan;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class LaporanPembelianPersediaanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $manajer;
    private Supplier $supplier;
    private BahanBaku $bahanBaku;
    private Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->manajer = User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
            'is_active' => true,
        ]);

        // Create Supplier
        $this->supplier = Supplier::create([
            'kode_supplier' => Supplier::generateKode(),
            'nama_supplier' => 'Supplier Susu Murni',
            'kontak' => '08123456789',
            'alamat' => 'Bandung',
            'saldo_hutang' => 0.00,
            'is_active' => true,
        ]);

        // Create Bahan Baku
        $this->bahanBaku = BahanBaku::create([
            'kode_bahan' => 'BHN-00001',
            'nama_bahan' => 'Susu Segar Mentah',
            'kategori' => 'Baku',
            'satuan' => 'Liter',
            'stok' => 10,
            'stok_minimum' => 5,
            'harga_rata_rata' => 10000.00,
            'supplier_utama_id' => $this->supplier->id,
            'is_active' => true,
        ]);

        // Create Produk
        $this->produk = Produk::create([
            'kode_produk' => 'PRD-00001',
            'nama_produk' => 'Susu Chocolate',
            'satuan' => 'Cup',
            'harga_jual' => 15000.00,
            'harga_pokok' => 8000.00,
            'stok' => 20,
            'stok_minimum' => 10,
            'is_active' => true,
        ]);
    }

    /**
     * Test Pembelian Module workflow and Moving Average Price calculation.
     */
    public function test_pembelian_flow_and_moving_average(): void
    {
        $this->actingAs($this->admin);

        // 1. Render index and create forms
        $response = $this->get('/pembelian');
        $response->assertOk();

        $response = $this->get('/pembelian/create');
        $response->assertOk();

        // 2. Submit new purchase order with "Belum Lunas" status
        // Formula test values:
        // Stok lama: 10, Harga lama: 10,000 -> Total nilai lama: 100,000
        // Pembelian: 5, Harga beli: 13,000 -> Total pembelian: 65,000
        // Stok baru: 15
        // Moving Average = (100,000 + 65,000) / 15 = 165,000 / 15 = 11,000
        $purchaseData = [
            'nomor_faktur' => 'FAK-TEST-001',
            'nomor_po' => 'PO-TEST-001',
            'supplier_id' => $this->supplier->id,
            'tanggal_faktur' => Carbon::now()->toDateString(),
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(7)->toDateString(),
            'status_bayar' => 'Belum Lunas',
            'items' => [
                [
                    'bahan_baku_id' => $this->bahanBaku->id,
                    'kuantitas' => 5,
                    'harga_satuan' => 13000.00,
                ]
            ],
            'catatan' => 'Pembelian test moving average',
        ];

        $response = $this->post('/pembelian', $purchaseData);
        $response->assertRedirect('/pembelian');

        // Check stock and moving average price
        $freshBahan = $this->bahanBaku->fresh();
        $this->assertEquals(15, $freshBahan->stok);
        $this->assertEquals(11000.00, (float) $freshBahan->harga_rata_rata);

        // Check supplier debt
        $freshSupplier = $this->supplier->fresh();
        $this->assertEquals(65000.00, (float) $freshSupplier->saldo_hutang);

        // Find created purchase
        $pembelian = Pembelian::where('nomor_faktur', 'FAK-TEST-001')->first();
        $this->assertNotNull($pembelian);
        $this->assertEquals('Belum Lunas', $pembelian->status_bayar);

        // 3. Perform partial debt payment
        $paymentData = [
            'tanggal_bayar' => Carbon::now()->toDateString(),
            'jumlah_bayar' => 30000.00,
            'metode' => 'Transfer Bank',
            'catatan' => 'Bayar cicilan pertama',
        ];

        $response = $this->post("/pembelian/{$pembelian->id}/bayar", $paymentData);
        $response->assertRedirect(route('pembelian.show', $pembelian->id));

        $this->assertEquals(35000.00, (float) $this->supplier->fresh()->saldo_hutang);
        $this->assertEquals('Belum Lunas', $pembelian->fresh()->status_bayar);

        // 4. Perform final debt payment to clear the balance
        $paymentData2 = [
            'tanggal_bayar' => Carbon::now()->toDateString(),
            'jumlah_bayar' => 35000.00,
            'metode' => 'Transfer Bank',
            'catatan' => 'Pelunasan',
        ];

        $response = $this->post("/pembelian/{$pembelian->id}/bayar", $paymentData2);
        $response->assertRedirect(route('pembelian.show', $pembelian->id));

        $this->assertEquals(0.00, (float) $this->supplier->fresh()->saldo_hutang);
        $this->assertEquals('Lunas', $pembelian->fresh()->status_bayar);
    }

    /**
     * Test Persediaan / Stock Adjustment and Kartu Stok.
     */
    public function test_persediaan_adjustment_and_kartu_stok(): void
    {
        $this->actingAs($this->admin);

        // 1. View persediaan index
        $response = $this->get('/persediaan');
        $response->assertOk();

        // 2. View adjustment page
        $response = $this->get('/persediaan/adjustment');
        $response->assertOk();

        // 3. Post stock adjustment
        // Initial stock is 20, adjustment to 18 (difference -2)
        $adjustmentData = [
            'item_type' => 'produk',
            'item_id' => $this->produk->id,
            'stok_fisik' => 18,
            'alasan' => 'Bocor atau rusak',
            'tanggal' => Carbon::now()->toDateString(),
        ];

        $response = $this->post('/persediaan/adjustment', $adjustmentData);
        $response->assertRedirect(route('persediaan.index', ['tab' => 'riwayat']));

        // Check stock updated
        $this->assertEquals(18, $this->produk->fresh()->stok);
        $this->assertDatabaseHas('stock_adjustments', [
            'item_type' => 'produk',
            'item_id' => $this->produk->id,
            'stok_sistem' => 20,
            'stok_fisik' => 18,
            'selisih' => -2,
        ]);

        // 4. Access Kartu Stok
        $response = $this->get("/persediaan/kartu-stok/produk/{$this->produk->id}");
        $response->assertOk();

        // 5. Export Kartu Stok Excel
        $response = $this->get("/persediaan/kartu-stok/produk/{$this->produk->id}/export");
        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * Test Laporan Keuangan Module actions, settings, and calculations.
     */
    public function test_laporan_financial_computations_and_actions(): void
    {
        $this->actingAs($this->admin);

        // 1. Access laporan page
        $response = $this->get('/laporan');
        $response->assertOk();

        // 2. Set Saldo Awal
        $saldoData = [
            'saldo_kas_awal' => 5000000.00,
        ];
        $response = $this->post('/laporan/saldo-awal', $saldoData);
        $response->assertRedirect(route('laporan.index', [
            'tab' => 'arus_kas',
            'dari' => null,
            'sampai' => null,
        ]));

        $this->assertEquals(5000000.00, (float) Pengaturan::getVal('saldo_kas_awal'));

        // 3. Add Beban Lain
        $bebanData = [
            'tanggal' => Carbon::now()->toDateString(),
            'keterangan' => 'Tagihan Listrik Bulanan',
            'nominal' => 250000.00,
        ];
        $response = $this->post('/laporan/beban', $bebanData);
        $response->assertRedirect(route('laporan.index', [
            'tab' => 'beban',
            'dari' => null,
            'sampai' => null,
        ]));

        $this->assertDatabaseHas('beban_lains', [
            'keterangan' => 'Tagihan Listrik Bulanan',
            'nominal' => 250000.00,
        ]);

        $beban = BebanLain::where('keterangan', 'Tagihan Listrik Bulanan')->first();
        $this->assertNotNull($beban);

        // 4. Delete Beban Lain
        $response = $this->delete("/laporan/beban/{$beban->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('beban_lains', [
            'id' => $beban->id,
        ]);

        // 5. Excel exports downloads
        $response = $this->get('/laporan/laba-rugi/export');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->get('/laporan/arus-kas/export');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->get('/laporan/neraca/export');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
