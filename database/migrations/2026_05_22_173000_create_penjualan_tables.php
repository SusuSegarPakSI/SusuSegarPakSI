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
        // 1. Penjualan Table
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi', 30)->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('diskon_nominal', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->enum('metode_bayar', ['Tunai', 'Transfer', 'QRIS']);
            $table->decimal('jumlah_bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);
            $table->enum('status', ['Lunas', 'Retur Sebagian', 'Retur Penuh'])->default('Lunas');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Penjualan Detail Table
        Schema::create('penjualan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('hpp_satuan', 15, 2);
            $table->integer('kuantitas');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 3. Penjualan Retur Table
        Schema::create('penjualan_returs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans')->cascadeOnDelete();
            $table->date('tanggal_retur');
            $table->text('alasan');
            $table->decimal('total_nilai_retur', 15, 2)->default(0);
            $table->boolean('kembalikan_stok')->default(true);
            $table->timestamps();
        });

        // 4. Penjualan Retur Detail Table
        Schema::create('penjualan_retur_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_id')->constrained('penjualan_returs')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks');
            $table->integer('kuantitas_retur');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_retur_details');
        Schema::dropIfExists('penjualan_returs');
        Schema::dropIfExists('penjualan_details');
        Schema::dropIfExists('penjualans');
    }
};
