<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Suppliers table (created first because bahan_baku has FK to it)
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_supplier', 20)->unique();
            $table->string('nama_supplier');
            $table->string('nama_kontak')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->decimal('saldo_hutang', 15, 2)->default(0);
            $table->decimal('saldo_awal_hutang', 15, 2)->default(0); // input by laporan module
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Produk table
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk', 20)->unique();
            $table->string('nama_produk');
            $table->string('kategori')->nullable();
            $table->string('satuan', 50);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->decimal('harga_pokok', 15, 2)->default(0);  // readonly, updated by produksi module
            $table->integer('stok')->default(0);                // readonly, managed by other modules
            $table->integer('stok_minimum')->default(0);
            $table->string('foto')->nullable();                 // path to storage/public/produk
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Bahan Baku table
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan', 20)->unique();
            $table->string('nama_bahan');
            $table->enum('kategori', ['Baku', 'Penolong', 'Kemasan'])->default('Baku');
            $table->string('satuan', 50);
            $table->integer('stok')->default(0);                // readonly, managed by persediaan module
            $table->integer('stok_minimum')->default(0);
            $table->decimal('harga_rata_rata', 15, 2)->default(0); // Moving Average, readonly - updated by pembelian
            $table->foreignId('supplier_utama_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Customers table
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelanggan', 20)->unique();
            $table->string('nama');
            $table->enum('tipe', ['Perorangan', 'Mitra'])->default('Perorangan');
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('saldo_piutang', 15, 2)->default(0);       // readonly, managed by penjualan module
            $table->decimal('saldo_awal_piutang', 15, 2)->default(0);  // input by laporan module (saldo awal)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('bahan_bakus');
        Schema::dropIfExists('produks');
        Schema::dropIfExists('suppliers');
    }
};
