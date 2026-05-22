<?php

namespace Tests\Feature;

use App\Models\BahanBaku;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RBACAndMasterDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest is redirected to login.
     */
    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test active user can login, inactive cannot.
     */
    public function test_user_login_status_check(): void
    {
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
            'is_active' => true,
        ]);

        $inactiveAdmin = User::create([
            'name' => 'Inactive Admin',
            'email' => 'inactive@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        // Active manager login succeeds
        $response = $this->post('/login', [
            'email' => 'manager@test.com',
            'password' => 'password',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($manager);

        // Logout
        $this->post('/logout');
        $this->assertGuest();

        // Inactive admin login fails
        $response = $this->post('/login', [
            'email' => 'inactive@test.com',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test Manajer Role Permissions.
     */
    public function test_manajer_role_permissions(): void
    {
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
            'is_active' => true,
        ]);

        $this->actingAs($manager);

        // Can access Dashboard (Full)
        $response = $this->get('/dashboard');
        $response->assertOk();

        // Can access Master Data Index (Read)
        $response = $this->get('/master');
        $response->assertOk();

        // Can view specific master data items
        $product = Produk::create([
            'kode_produk' => 'PRD-00001',
            'nama_produk' => 'Susu Chocolate',
            'satuan' => 'Liter',
            'harga_jual' => 15000,
            'stok' => 10,
            'stok_minimum' => 5,
        ]);
        $response = $this->get("/master/produk/{$product->id}");
        $response->assertOk();

        // CANNOT access create/store/edit/update/toggle in Master Data (Admin Only)
        $response = $this->get('/master/produk/new/create');
        $response->assertStatus(403);

        $response = $this->post('/master/produk', [
            'nama_produk' => 'New Product',
            'satuan' => 'Liter',
            'harga_jual' => 10000,
            'stok_minimum' => 5,
        ]);
        $response->assertStatus(403);

        $response = $this->get("/master/produk/{$product->id}/edit");
        $response->assertStatus(403);

        $response = $this->put("/master/produk/{$product->id}", [
            'nama_produk' => 'Updated Product',
            'satuan' => 'Liter',
            'harga_jual' => 10000,
            'stok_minimum' => 5,
        ]);
        $response->assertStatus(403);

        $response = $this->patch("/master/produk/{$product->id}/toggle");
        $response->assertStatus(403);

        // CAN access User Management (Full)
        $response = $this->get('/pengaturan/users');
        $response->assertOk();

        $response = $this->post('/pengaturan/users', [
            'name' => 'New Admin',
            'email' => 'newadmin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertRedirect('/pengaturan/users');
        $this->assertDatabaseHas('users', ['email' => 'newadmin@test.com']);
    }

    /**
     * Test Admin Role Permissions.
     */
    public function test_admin_role_permissions(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        // Can access Dashboard (Read)
        $response = $this->get('/dashboard');
        $response->assertOk();

        // CANNOT access User Management (Manager Only)
        $response = $this->get('/pengaturan/users');
        $response->assertStatus(403);

        // CAN access Master Data (Full)
        $response = $this->get('/master/produk/new/create');
        $response->assertOk();

        $response = $this->post('/master/produk', [
            'nama_produk' => 'New Product',
            'satuan' => 'Liter',
            'harga_jual' => 10000,
            'stok_minimum' => 5,
        ]);
        $response->assertRedirect(route('master.index', ['tab' => 'produk']));
        $this->assertDatabaseHas('produks', ['nama_produk' => 'New Product']);

        $product = Produk::where('nama_produk', 'New Product')->first();

        $response = $this->get("/master/produk/{$product->id}/edit");
        $response->assertOk();

        $response = $this->put("/master/produk/{$product->id}", [
            'nama_produk' => 'Updated Product Name',
            'satuan' => 'Liter',
            'harga_jual' => 12000,
            'stok_minimum' => 5,
        ]);
        $response->assertRedirect(route('master.index', ['tab' => 'produk']));
        $this->assertDatabaseHas('produks', ['nama_produk' => 'Updated Product Name']);

        // Toggle active status
        $this->assertTrue($product->is_active);
        $response = $this->patch("/master/produk/{$product->id}/toggle");
        $response->assertRedirect(route('master.index', ['tab' => 'produk']));
        $this->assertFalse($product->fresh()->is_active);
    }
}
