<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\PenjualanRetur;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class POSModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;
    private User $admin;
    private Customer $customer;
    private Produk $milk;
    private Produk $cheese;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create a Manager User
        $this->manager = User::create([
            'name' => 'Manager Pak Si',
            'email' => 'manager@paksi.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
            'is_active' => true,
        ]);

        // 2. Create an Admin User
        $this->admin = User::create([
            'name' => 'Admin Operator',
            'email' => 'admin@paksi.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 3. Create a Customer
        $this->customer = Customer::create([
            'kode_pelanggan' => 'CUST-001',
            'nama' => 'Budi Santoso',
            'tipe' => 'Perorangan',
            'is_active' => true,
        ]);

        // 4. Create active Products
        $this->milk = Produk::create([
            'kode_produk' => 'PRD-MILK01',
            'nama_produk' => 'Susu Segar Murni 1L',
            'satuan' => 'Pcs',
            'harga_jual' => 20000,
            'harga_pokok' => 12000, // HPP snapshot
            'stok' => 50,
            'is_active' => true,
        ]);

        $this->cheese = Produk::create([
            'kode_produk' => 'PRD-CHS01',
            'nama_produk' => 'Keju Mozzarella 250g',
            'satuan' => 'Pcs',
            'harga_jual' => 35000,
            'harga_pokok' => 22000,
            'stok' => 20,
            'is_active' => true,
        ]);
    }

    /**
     * Test Guest: Directs to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('penjualan.index'))->assertRedirect('/login');
        $this->get(route('penjualan.create'))->assertRedirect('/login');
        $this->get('/penjualan/1')->assertRedirect('/login');
        $this->get('/penjualan/1/cetak')->assertRedirect('/login');
    }

    /**
     * Test Role Access Control:
     * - BOTH roles can view transaction index & show details.
     * - ONLY ADMIN can checkout (create & store) and process returns.
     * - MANAGER gets 403 on write & return actions.
     */
    public function test_role_based_access_control(): void
    {
        // ── MANAGER ROLE TESTS ──
        $this->actingAs($this->manager);

        // Can read index & show (we will create a transaction to show)
        $this->get(route('penjualan.index'))->assertOk();

        // CANNOT access checkout screen
        $this->get(route('penjualan.create'))->assertStatus(403);

        // CANNOT store checkout
        $this->post(route('penjualan.store'), [])->assertStatus(403);

        // Prepare a transaction for testing show & retur limits
        $penjualan = Penjualan::create([
            'nomor_transaksi' => 'INV-20260522-0001',
            'tanggal' => '2026-05-22',
            'customer_id' => $this->customer->id,
            'subtotal' => 40000,
            'diskon_nominal' => 0,
            'grand_total' => 40000,
            'metode_bayar' => 'Tunai',
            'jumlah_bayar' => 50000,
            'kembalian' => 10000,
            'status' => 'Lunas',
            'created_by' => $this->admin->id,
        ]);

        // Manager CAN see transaction details
        $this->get(route('penjualan.show', $penjualan->id))->assertOk();

        // Manager CANNOT process returns
        $this->post(route('penjualan.retur', $penjualan->id), [
            'tanggal_retur' => '2026-05-22',
            'alasan' => 'Bocor',
            'kembalikan_stok' => true,
            'items' => [$this->milk->id => 1]
        ])->assertStatus(403);


        // ── ADMIN ROLE TESTS ──
        $this->actingAs($this->admin);

        // Can read index
        $this->get(route('penjualan.index'))->assertOk();

        // CAN access checkout screen
        $this->get(route('penjualan.create'))->assertOk();

        // CAN view transaction details
        $this->get(route('penjualan.show', $penjualan->id))->assertOk();

        // CAN view thermal receipt
        $this->get(route('penjualan.cetak', $penjualan->id))->assertOk();
    }

    /**
     * Test Successful POS Checkout:
     * - Stock decrements correctly.
     * - Atomic INV number is generated.
     * - Exact HPP snapshot is recorded.
     * - Money maths (Subtotal, Grand Total, Change) are accurate.
     */
    public function test_successful_pos_checkout(): void
    {
        $this->actingAs($this->admin);

        // Initial product stock levels
        $this->assertEquals(50, $this->milk->stok);
        $this->assertEquals(20, $this->cheese->stok);

        $payload = [
            'tanggal' => '2026-05-22',
            'customer_id' => $this->customer->id,
            'diskon_nominal' => 5000,
            'metode_bayar' => 'Tunai',
            'jumlah_bayar' => 100000,
            'catatan' => 'Pesanan POS Cepat',
            'items' => [
                [
                    'produk_id' => $this->milk->id,
                    'kuantitas' => 2, // 2 * 20.000 = 40.000
                ],
                [
                    'produk_id' => $this->cheese->id,
                    'kuantitas' => 1, // 1 * 35.000 = 35.000
                ],
            ]
        ];

        // Total subtotal: 75.000. Discount: 5.000. Grand Total: 70.000. Kembalian: 30.000

        $response = $this->post(route('penjualan.store'), $payload);
        $response->assertRedirect(route('penjualan.index'));
        $response->assertSessionHas('success');

        // Check stock decremented
        $this->assertEquals(48, $this->milk->fresh()->stok);
        $this->assertEquals(19, $this->cheese->fresh()->stok);

        // Check transaction saved
        $penjualan = Penjualan::orderBy('id', 'desc')->first();
        $this->assertNotNull($penjualan);
        $this->assertEquals('INV-20260522-0001', $penjualan->nomor_transaksi);
        $this->assertEquals(75000, $penjualan->subtotal);
        $this->assertEquals(5000, $penjualan->diskon_nominal);
        $this->assertEquals(70000, $penjualan->grand_total);
        $this->assertEquals(100000, $penjualan->jumlah_bayar);
        $this->assertEquals(30000, $penjualan->kembalian);
        $this->assertEquals('Tunai', $penjualan->metode_bayar);
        $this->assertEquals('Lunas', $penjualan->status);

        // Check details and snapshot HPP
        $this->assertCount(2, $penjualan->details);

        $milkDetail = $penjualan->details()->where('produk_id', $this->milk->id)->first();
        $this->assertNotNull($milkDetail);
        $this->assertEquals(2, $milkDetail->kuantitas);
        $this->assertEquals(20000, $milkDetail->harga_satuan);
        $this->assertEquals(12000, $milkDetail->hpp_satuan); // Snapshot of milk's cost poko
        $this->assertEquals(40000, $milkDetail->subtotal);

        $cheeseDetail = $penjualan->details()->where('produk_id', $this->cheese->id)->first();
        $this->assertNotNull($cheeseDetail);
        $this->assertEquals(1, $cheeseDetail->kuantitas);
        $this->assertEquals(35000, $cheeseDetail->harga_satuan);
        $this->assertEquals(22000, $cheeseDetail->hpp_satuan); // Snapshot of cheese's cost poko
        $this->assertEquals(35000, $cheeseDetail->subtotal);
    }

    /**
     * Test Non-Cash payment validation (QRIS/Transfer overrides jumlah_bayar to grand_total)
     */
    public function test_pos_non_cash_checkout_adjusts_payment(): void
    {
        $this->actingAs($this->admin);

        $payload = [
            'tanggal' => '2026-05-22',
            'customer_id' => $this->customer->id,
            'diskon_nominal' => 0,
            'metode_bayar' => 'QRIS',
            'jumlah_bayar' => 0, // Should be automatically set to grand_total
            'items' => [
                [
                    'produk_id' => $this->milk->id,
                    'kuantitas' => 2, // 40.000
                ],
            ]
        ];

        $response = $this->post(route('penjualan.store'), $payload);
        $response->assertRedirect(route('penjualan.index'));

        $penjualan = Penjualan::orderBy('id', 'desc')->first();
        $this->assertEquals(40000, $penjualan->grand_total);
        $this->assertEquals(40000, $penjualan->jumlah_bayar); // Adjusted
        $this->assertEquals(0, $penjualan->kembalian);
    }

    /**
     * Test Checkout Stock Validation Failures:
     * - Fails if requested quantity exceeds stock.
     * - Fails if product is inactive.
     */
    public function test_checkout_validation_failures(): void
    {
        $this->actingAs($this->admin);

        // 1. Stock insufficient
        $payload = [
            'tanggal' => '2026-05-22',
            'customer_id' => $this->customer->id,
            'diskon_nominal' => 0,
            'metode_bayar' => 'Tunai',
            'jumlah_bayar' => 2000000,
            'items' => [
                [
                    'produk_id' => $this->milk->id,
                    'kuantitas' => 999, // Fails since stock is 50
                ],
            ]
        ];

        $response = $this->post(route('penjualan.store'), $payload);
        $response->assertSessionHasErrors();
        
        // Stock remains untouched
        $this->assertEquals(50, $this->milk->fresh()->stok);

        // 2. Inactive product
        $inactiveProduct = Produk::create([
            'kode_produk' => 'PRD-INACTIVE',
            'nama_produk' => 'Susu Kadaluarsa',
            'satuan' => 'Pcs',
            'harga_jual' => 10000,
            'harga_pokok' => 5000,
            'stok' => 100,
            'is_active' => false,
        ]);

        $payloadInactive = [
            'tanggal' => '2026-05-22',
            'customer_id' => $this->customer->id,
            'diskon_nominal' => 0,
            'metode_bayar' => 'Tunai',
            'jumlah_bayar' => 20000,
            'items' => [
                [
                    'produk_id' => $inactiveProduct->id,
                    'kuantitas' => 1,
                ],
            ]
        ];

        $response2 = $this->post(route('penjualan.store'), $payloadInactive);
        $response2->assertSessionHasErrors();
    }

    /**
     * Test Retur Processing:
     * - Stock is conditionally restored based on option.
     * - Parent status state transitions: Lunas -> Retur Sebagian -> Retur Penuh.
     * - Cannot return more than originally purchased.
     */
    public function test_retur_processing_behavior(): void
    {
        $this->actingAs($this->admin);

        // 1. Create a base sale transaction
        $penjualan = Penjualan::create([
            'nomor_transaksi' => 'INV-20260522-0002',
            'tanggal' => '2026-05-22',
            'customer_id' => $this->customer->id,
            'subtotal' => 110000, // 2 milk (40.000) + 2 cheese (70.000)
            'diskon_nominal' => 0,
            'grand_total' => 110000,
            'metode_bayar' => 'Tunai',
            'jumlah_bayar' => 110000,
            'kembalian' => 0,
            'status' => 'Lunas',
            'created_by' => $this->admin->id,
        ]);

        $detail1 = PenjualanDetail::create([
            'penjualan_id' => $penjualan->id,
            'produk_id' => $this->milk->id,
            'harga_satuan' => 20000,
            'hpp_satuan' => 12000,
            'kuantitas' => 2,
            'subtotal' => 40000,
        ]);

        $detail2 = PenjualanDetail::create([
            'penjualan_id' => $penjualan->id,
            'produk_id' => $this->cheese->id,
            'harga_satuan' => 35000,
            'hpp_satuan' => 22000,
            'kuantitas' => 2,
            'subtotal' => 70000,
        ]);

        // Stocks are currently 50 (milk) and 20 (cheese) - they were not altered by this manual create
        $this->assertEquals(50, $this->milk->stok);
        $this->assertEquals(20, $this->cheese->stok);

        // ── PART 1: RETUR SEBAGIAN (Partial Return with Stock Restoration) ──
        // Return 1 Milk, restoring stock
        $payloadPartial = [
            'tanggal_retur' => '2026-05-22',
            'alasan' => 'Kemasan Sobek',
            'kembalikan_stok' => '1',
            'items' => [
                $this->milk->id => 1,
                $this->cheese->id => 0,
            ],
        ];

        $response = $this->post(route('penjualan.retur', $penjualan->id), $payloadPartial);
        $response->assertRedirect(route('penjualan.show', $penjualan->id));

        // Check parent status transitioned to 'Retur Sebagian'
        $this->assertEquals('Retur Sebagian', $penjualan->fresh()->status);

        // Check stock of milk is restored by 1
        $this->assertEquals(51, $this->milk->fresh()->stok);
        // Cheese stock remains 20
        $this->assertEquals(20, $this->cheese->fresh()->stok);

        // Check retur records
        $this->assertDatabaseHas('penjualan_returs', [
            'penjualan_id' => $penjualan->id,
            'total_nilai_retur' => 20000,
            'kembalikan_stok' => true,
        ]);


        // ── PART 2: ATTEMPT OVER-RETURN (Should fail) ──
        // Milk total purchased was 2. Already returned 1. Trying to return 2 more (total 3) must fail.
        $payloadInvalid = [
            'tanggal_retur' => '2026-05-22',
            'alasan' => 'Curang',
            'kembalikan_stok' => '1',
            'items' => [
                $this->milk->id => 2,
            ],
        ];

        $responseInvalid = $this->post(route('penjualan.retur', $penjualan->id), $payloadInvalid);
        $responseInvalid->assertSessionHasErrors();


        // ── PART 3: RETUR PENUH (Complete Remaining Returns without Stock Restoration) ──
        // Return remaining 1 Milk and 2 Cheese. Do NOT restore stock for cheese (e.g. damaged goods).
        $payloadFull = [
            'tanggal_retur' => '2026-05-22',
            'alasan' => 'Kadaluarsa dan Rusak',
            'kembalikan_stok' => '0', // No stock restoration
            'items' => [
                $this->milk->id => 1, // Remaining 1 milk
                $this->cheese->id => 2, // Remaining 2 cheese
            ],
        ];

        $responseFull = $this->post(route('penjualan.retur', $penjualan->id), $payloadFull);
        $responseFull->assertRedirect(route('penjualan.show', $penjualan->id));

        // Check parent status is now 'Retur Penuh'
        $this->assertEquals('Retur Penuh', $penjualan->fresh()->status);

        // Milk stock has not been increased (it's still 51 since kembalikan_stok was '0')
        $this->assertEquals(51, $this->milk->fresh()->stok);
        // Cheese stock has not been restored (it is still 20)
        $this->assertEquals(20, $this->cheese->fresh()->stok);

        // Check total returns count
        $this->assertCount(2, $penjualan->returs);
    }
}
