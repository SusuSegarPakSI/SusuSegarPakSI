<?php

use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (will hit auth middleware and redirect to login)
Route::redirect('/', '/dashboard');

// Routes for both Manajer and Admin
Route::middleware(['auth', 'role:manajer,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Master Data ────────────────────────────────────────────
    Route::prefix('master')->name('master.')->group(function () {

        // Unified tabbed index
        Route::get('/', [MasterDataController::class, 'index'])->name('index');

        // ── Produk ──
        // NOTE: 'create' route must be defined BEFORE '{produk}' show route to avoid conflict
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');

        Route::middleware('role:admin')->group(function () {
            Route::get('/produk/new/create', [ProdukController::class, 'create'])->name('produk.create');
            Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
            Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
            Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
            Route::patch('/produk/{produk}/toggle', [ProdukController::class, 'toggle'])->name('produk.toggle');
        });

        // ── Bahan Baku ──
        Route::get('/bahan-baku', [BahanBakuController::class, 'index'])->name('bahan-baku.index');
        Route::get('/bahan-baku/{bahanBaku}', [BahanBakuController::class, 'show'])->name('bahan-baku.show');

        Route::middleware('role:admin')->group(function () {
            Route::get('/bahan-baku/new/create', [BahanBakuController::class, 'create'])->name('bahan-baku.create');
            Route::post('/bahan-baku', [BahanBakuController::class, 'store'])->name('bahan-baku.store');
            Route::get('/bahan-baku/{bahanBaku}/edit', [BahanBakuController::class, 'edit'])->name('bahan-baku.edit');
            Route::put('/bahan-baku/{bahanBaku}', [BahanBakuController::class, 'update'])->name('bahan-baku.update');
            Route::patch('/bahan-baku/{bahanBaku}/toggle', [BahanBakuController::class, 'toggle'])->name('bahan-baku.toggle');
        });

        // ── Pelanggan (Customer) ──
        Route::get('/pelanggan', [CustomerController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/{pelanggan}', [CustomerController::class, 'show'])->name('pelanggan.show');

        Route::middleware('role:admin')->group(function () {
            Route::get('/pelanggan/new/create', [CustomerController::class, 'create'])->name('pelanggan.create');
            Route::post('/pelanggan', [CustomerController::class, 'store'])->name('pelanggan.store');
            Route::get('/pelanggan/{pelanggan}/edit', [CustomerController::class, 'edit'])->name('pelanggan.edit');
            Route::put('/pelanggan/{pelanggan}', [CustomerController::class, 'update'])->name('pelanggan.update');
            Route::patch('/pelanggan/{pelanggan}/toggle', [CustomerController::class, 'toggle'])->name('pelanggan.toggle');
        });

        // ── Supplier ──
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('/supplier/{supplier}', [SupplierController::class, 'show'])->name('supplier.show');

        Route::middleware('role:admin')->group(function () {
            Route::get('/supplier/new/create', [SupplierController::class, 'create'])->name('supplier.create');
            Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
            Route::get('/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
            Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
            Route::patch('/supplier/{supplier}/toggle', [SupplierController::class, 'toggle'])->name('supplier.toggle');
        });
    });

    // ─── Penjualan POS ───────────────────────────────────────────
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PenjualanController::class, 'index'])->name('index');
        
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [\App\Http\Controllers\PenjualanController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\PenjualanController::class, 'store'])->name('store');
            Route::post('/{penjualan}/retur', [\App\Http\Controllers\PenjualanController::class, 'retur'])->name('retur');
        });

        Route::get('/{penjualan}', [\App\Http\Controllers\PenjualanController::class, 'show'])->name('show');
        Route::get('/{penjualan}/cetak', [\App\Http\Controllers\PenjualanController::class, 'cetakStruk'])->name('cetak');
    });
    // ─── Produksi & HPP ──────────────────────────────────────────
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProduksiController::class, 'index'])->name('index');
        
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [\App\Http\Controllers\ProduksiController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\ProduksiController::class, 'store'])->name('store');
            Route::post('/{id}/mulai', [\App\Http\Controllers\ProduksiController::class, 'mulai'])->name('mulai');
            Route::post('/{id}/selesaikan', [\App\Http\Controllers\ProduksiController::class, 'selesaikan'])->name('selesaikan');
            Route::post('/{id}/batalkan', [\App\Http\Controllers\ProduksiController::class, 'batalkan'])->name('batalkan');
        });
        
        Route::get('/{id}', [\App\Http\Controllers\ProduksiController::class, 'show'])->name('show');
    });

    Route::prefix('bom')->name('bom.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProduksiController::class, 'bomIndex'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::post('/{produkId}', [\App\Http\Controllers\ProduksiController::class, 'bomUpdate'])->name('update');
        });
    });
    Route::get('/pembelian', fn() => view('placeholder', ['title' => 'Pembelian']))->name('pembelian.index');
    Route::get('/persediaan', fn() => view('placeholder', ['title' => 'Persediaan']))->name('persediaan.index');
    Route::get('/sdm', fn() => view('placeholder', ['title' => 'SDM & Penggajian']))->name('sdm.index');
    Route::get('/laporan', fn() => view('placeholder', ['title' => 'Laporan Keuangan']))->name('laporan.index');
});

// Routes for Manajer ONLY
Route::middleware(['auth', 'role:manajer'])->group(function () {
    // User management resource
    Route::resource('pengaturan/users', UserController::class)
        ->only(['index', 'store', 'update'])
        ->names([
            'index'  => 'users.index',
            'store'  => 'users.store',
            'update' => 'users.update',
        ]);

    // Active/Inactive toggle
    Route::patch('pengaturan/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    // General settings
    Route::get('/pengaturan', fn() => view('placeholder', ['title' => 'Pengaturan Sistem']))->name('pengaturan.index');
});

require __DIR__.'/auth.php';
