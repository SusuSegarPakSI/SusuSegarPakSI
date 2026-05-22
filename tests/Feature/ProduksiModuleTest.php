<?php

namespace Tests\Feature;

use App\Models\BahanBaku;
use App\Models\BillOfMaterial;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\ProduksiBahanPakai;
use App\Models\ProduksiBiaya;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProduksiModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected User $admin;
    protected Produk $produk;
    protected BahanBaku $bahanBaku;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Users
        $this->manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Create standard product
        $this->produk = Produk::create([
            'kode_produk' => 'PRD-001',
            'nama_produk' => 'Susu Segar Coklat 1L',
            'satuan' => 'Botol',
            'harga_jual' => 15000,
            'harga_pokok' => 8000,
            'stok' => 10,
            'stok_minimum' => 5,
            'is_active' => true,
        ]);

        // 3. Create standard raw material
        $this->bahanBaku = BahanBaku::create([
            'kode_bahan' => 'BB-001',
            'nama_bahan' => 'Susu Murni Segar',
            'satuan' => 'Liter',
            'stok' => 50,
            'harga_rata_rata' => 5000,
            'is_active' => true,
        ]);
    }

    /**
     * Test Guest is redirected, Manager is read-only, and Admin is fully authorized.
     */
    public function test_produksi_rbac_access_control(): void
    {
        // ═══ A. GUEST ═══
        $response = $this->get('/produksi');
        $response->assertRedirect('/login');

        $response = $this->get('/bom');
        $response->assertRedirect('/login');

        // ═══ B. MANAJER (Read-Only) ═══
        $this->actingAs($this->manager);

        // Can read index
        $response = $this->get('/produksi');
        $response->assertOk();

        // Can read BOM Index
        $response = $this->get('/bom');
        $response->assertOk();

        // Cannot create or store
        $response = $this->get('/produksi/create');
        $response->assertStatus(403);

        $response = $this->post('/produksi', []);
        $response->assertStatus(403);

        // Create a dummy produksi run to test show and post actions
        $produksi = Produksi::create([
            'nomor_produksi' => 'PRD-20260522-0001',
            'tanggal_mulai' => '2026-05-22',
            'nama_batch' => 'Batch 1',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 1,
            'target_output' => 10,
            'status' => 'Draft',
            'created_by' => $this->admin->id,
        ]);

        // Can read specific run
        $response = $this->get("/produksi/{$produksi->id}");
        $response->assertOk();

        // Cannot trigger mulai, selesaikan, or batalkan
        $response = $this->post("/produksi/{$produksi->id}/mulai");
        $response->assertStatus(403);

        $response = $this->post("/produksi/{$produksi->id}/selesaikan", ['actual_output' => 10]);
        $response->assertStatus(403);

        $response = $this->post("/produksi/{$produksi->id}/batalkan");
        $response->assertStatus(403);

        // Cannot update BOM
        $response = $this->post("/bom/{$this->produk->id}", []);
        $response->assertStatus(403);

        $this->post('/logout');

        // ═══ C. ADMIN (Full CRUD) ═══
        $this->actingAs($this->admin);

        // Can read index & create form
        $this->get('/produksi')->assertOk();
        $this->get('/produksi/create')->assertOk();
        $this->get('/bom')->assertOk();
    }

    /**
     * Test stock validation checks inside @store.
     */
    public function test_production_bom_stock_validation(): void
    {
        $this->actingAs($this->admin);

        // 1. Create a recipe standard BOM: Susu Segar Coklat 1L requires 2 Liters of Susu Murni Segar per batch
        BillOfMaterial::create([
            'produk_id' => $this->produk->id,
            'bahan_baku_id' => $this->bahanBaku->id,
            'kuantitas_per_batch' => 2.00,
            'satuan' => 'Liter',
        ]);

        // Scenario A: Insufficient Stock
        // We demand 30 batches -> requires 60 Liters. But we only have 50 Liters in stock.
        $payloadInsufficient = [
            'nama_batch' => 'Batch Lebar',
            'tanggal_mulai' => '2026-05-22',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 30, // 30 * 2 = 60 required
            'target_output' => 30,
            'status' => 'Proses',
            'bahan_baku' => [$this->bahanBaku->id => 300000],
            'bahan_baku_kuantitas' => [$this->bahanBaku->id => 60],
            'tenaga_kerja_nominal' => 50000,
        ];

        $response = $this->post('/produksi', $payloadInsufficient);
        $response->assertSessionHasErrors('stok');
        // Assert no production record was created
        $this->assertDatabaseEmpty('produksi');
        // Assert raw material stock is unchanged
        $this->assertEquals(50, $this->bahanBaku->fresh()->stok);

        // Scenario B: Sufficient Stock
        // We demand 10 batches -> requires 20 Liters. 50 Liters are available.
        $payloadSufficient = [
            'nama_batch' => 'Batch Cukup',
            'tanggal_mulai' => '2026-05-22',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 10, // 10 * 2 = 20 required
            'target_output' => 10,
            'status' => 'Proses',
            'bahan_baku' => [$this->bahanBaku->id => 100000], // 20 * 5000 = 100k
            'bahan_baku_kuantitas' => [$this->bahanBaku->id => 20],
            'tenaga_kerja_nominal' => 20000,
        ];

        $response = $this->post('/produksi', $payloadSufficient);
        $response->assertRedirect('/produksi');
        $response->assertSessionHasNoErrors();

        // Assert production record exists
        $this->assertDatabaseHas('produksi', [
            'nama_batch' => 'Batch Cukup',
            'status' => 'Proses',
        ]);

        // Assert raw material stock was decremented correctly (50 - 20 = 30)
        $this->assertEquals(30, $this->bahanBaku->fresh()->stok);

        // Assert snapshot was correctly recorded in produksi_bahan_pakai
        $produksi = Produksi::where('nama_batch', 'Batch Cukup')->first();
        $this->assertDatabaseHas('produksi_bahan_pakai', [
            'produksi_id' => $produksi->id,
            'bahan_baku_id' => $this->bahanBaku->id,
            'kuantitas_pakai' => 20,
            'harga_satuan_saat_itu' => 5000,
        ]);
    }

    /**
     * Test cancellation of a production batch restores raw materials.
     */
    public function test_production_cancellation_restores_stock(): void
    {
        $this->actingAs($this->admin);

        // 1. Setup BOM
        BillOfMaterial::create([
            'produk_id' => $this->produk->id,
            'bahan_baku_id' => $this->bahanBaku->id,
            'kuantitas_per_batch' => 5.00,
            'satuan' => 'Liter',
        ]);

        // 2. Start a batch (Proses) -> demands 5 Liters. Stock will go from 50 to 45.
        $payload = [
            'nama_batch' => 'Batch Batal',
            'tanggal_mulai' => '2026-05-22',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 1,
            'target_output' => 5,
            'status' => 'Proses',
            'bahan_baku' => [$this->bahanBaku->id => 25000],
            'bahan_baku_kuantitas' => [$this->bahanBaku->id => 5],
            'tenaga_kerja_nominal' => 10000,
        ];

        $this->post('/produksi', $payload);
        $this->assertEquals(45, $this->bahanBaku->fresh()->stok);

        $produksi = Produksi::where('nama_batch', 'Batch Batal')->first();

        // 3. Cancel the batch
        $response = $this->post("/produksi/{$produksi->id}/batalkan");
        $response->assertRedirect(route('produksi.show', $produksi));

        // 4. Assert status is Dibatalkan and raw material stock is restored to 50
        $this->assertEquals('Dibatalkan', $produksi->fresh()->status);
        $this->assertEquals(50, $this->bahanBaku->fresh()->stok);
    }

    /**
     * Test moving average HPP and finished goods stock update upon completion.
     */
    public function test_production_completion_and_moving_average_hpp(): void
    {
        $this->actingAs($this->admin);

        // 1. Setup BOM
        BillOfMaterial::create([
            'produk_id' => $this->produk->id,
            'bahan_baku_id' => $this->bahanBaku->id,
            'kuantitas_per_batch' => 1.00,
            'satuan' => 'Liter',
        ]);

        // We will run 4 sequential production batches.
        // The moving average updates the product's HPP based on the average of the last 3 completed runs.
        
        // --- BATCH 1 ---
        // Cost: Raw materials = 5,000 + Labor = 5,000. Total = 10,000. Actual Output = 10.
        // HPP = 10,000 / 10 = 1,000.
        $produksi1 = Produksi::create([
            'nomor_produksi' => 'PRD-1',
            'tanggal_mulai' => '2026-05-22',
            'nama_batch' => 'Batch 1',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 1,
            'target_output' => 10,
            'status' => 'Proses',
            'created_by' => $this->admin->id,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi1->id,
            'jenis_biaya' => 'bahan_baku',
            'keterangan' => 'Bahan Baku',
            'nominal' => 5000,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi1->id,
            'jenis_biaya' => 'tenaga_kerja',
            'keterangan' => 'Labor',
            'nominal' => 5000,
        ]);

        // Complete Batch 1
        $this->post("/produksi/{$produksi1->id}/selesaikan", ['actual_output' => 10]);
        // Fresh product HPP should be exactly HPP 1 = 1,000 (since only 1 completed batch exists)
        $this->assertEquals(1000, $this->produk->fresh()->harga_pokok);
        // Finished stock should be updated (Original 10 + Batch 1 actual output 10 = 20)
        $this->assertEquals(20, $this->produk->fresh()->stok);

        // --- BATCH 2 ---
        // Cost: Raw materials = 5,000 + Labor = 25,000. Total = 30,000. Actual Output = 10.
        // HPP = 30,000 / 10 = 3,000.
        $produksi2 = Produksi::create([
            'nomor_produksi' => 'PRD-2',
            'tanggal_mulai' => '2026-05-22',
            'nama_batch' => 'Batch 2',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 1,
            'target_output' => 10,
            'status' => 'Proses',
            'created_by' => $this->admin->id,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi2->id,
            'jenis_biaya' => 'bahan_baku',
            'keterangan' => 'Bahan Baku',
            'nominal' => 5000,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi2->id,
            'jenis_biaya' => 'tenaga_kerja',
            'keterangan' => 'Labor',
            'nominal' => 25000,
        ]);

        // Complete Batch 2
        $this->post("/produksi/{$produksi2->id}/selesaikan", ['actual_output' => 10]);
        // Moving average of 2 batches: (1000 + 3000) / 2 = 2000
        $this->assertEquals(2000, $this->produk->fresh()->harga_pokok);
        $this->assertEquals(30, $this->produk->fresh()->stok);

        // --- BATCH 3 ---
        // Cost: Raw materials = 5,000 + Labor = 45,000. Total = 50,000. Actual Output = 10.
        // HPP = 50,000 / 10 = 5,000.
        $produksi3 = Produksi::create([
            'nomor_produksi' => 'PRD-3',
            'tanggal_mulai' => '2026-05-22',
            'nama_batch' => 'Batch 3',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 1,
            'target_output' => 10,
            'status' => 'Proses',
            'created_by' => $this->admin->id,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi3->id,
            'jenis_biaya' => 'bahan_baku',
            'keterangan' => 'Bahan Baku',
            'nominal' => 5000,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi3->id,
            'jenis_biaya' => 'tenaga_kerja',
            'keterangan' => 'Labor',
            'nominal' => 45000,
        ]);

        // Complete Batch 3
        $this->post("/produksi/{$produksi3->id}/selesaikan", ['actual_output' => 10]);
        // Moving average of last 3 batches: (1000 + 3000 + 5000) / 3 = 3000
        $this->assertEquals(3000, $this->produk->fresh()->harga_pokok);
        $this->assertEquals(40, $this->produk->fresh()->stok);

        // --- BATCH 4 (THE LIMIT CAP CHECK) ---
        // Cost: Raw materials = 5,000 + Labor = 85,000. Total = 90,000. Actual Output = 10.
        // HPP = 90,000 / 10 = 9,000.
        $produksi4 = Produksi::create([
            'nomor_produksi' => 'PRD-4',
            'tanggal_mulai' => '2026-05-22',
            'nama_batch' => 'Batch 4',
            'produk_output_id' => $this->produk->id,
            'jumlah_batch' => 1,
            'target_output' => 10,
            'status' => 'Proses',
            'created_by' => $this->admin->id,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi4->id,
            'jenis_biaya' => 'bahan_baku',
            'keterangan' => 'Bahan Baku',
            'nominal' => 5000,
        ]);
        ProduksiBiaya::create([
            'produksi_id' => $produksi4->id,
            'jenis_biaya' => 'tenaga_kerja',
            'keterangan' => 'Labor',
            'nominal' => 85000,
        ]);

        // Complete Batch 4
        $this->post("/produksi/{$produksi4->id}/selesaikan", ['actual_output' => 10]);
        
        // Crucial check: HPP should be the moving average of the *last 3* completed batches.
        // The last 3 completed batches are: Batch 2 (3,000), Batch 3 (5,000), Batch 4 (9,000).
        // (Batch 1 is discarded from the window because we only average the last 3).
        // Moving Average = (3,000 + 5,000 + 9,000) / 3 = 17,000 / 3 = 5,666.67
        $expectedMovingAverage = (3000 + 5000 + 9000) / 3;
        $this->assertEqualsWithDelta($expectedMovingAverage, $this->produk->fresh()->harga_pokok, 0.01);
        
        // Finished product stock check: (Original 10 + 10 + 10 + 10 + 10 = 50)
        $this->assertEquals(50, $this->produk->fresh()->stok);
    }
}
