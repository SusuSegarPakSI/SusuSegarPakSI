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
        // 1. Bill of Materials
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->cascadeOnDelete();
            $table->decimal('kuantitas_per_batch', 10, 2);
            $table->string('satuan');
            $table->timestamps();
        });

        // 2. Produksi
        Schema::create('produksi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_produksi')->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('nama_batch');
            $table->foreignId('produk_output_id')->constrained('produks')->cascadeOnDelete();
            $table->integer('jumlah_batch')->default(1);
            $table->integer('target_output');
            $table->integer('actual_output')->nullable();
            $table->enum('status', ['Draft', 'Proses', 'Selesai', 'Dibatalkan'])->default('Draft');
            $table->decimal('hpp_per_unit', 12, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Produksi Biaya
        Schema::create('produksi_biaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksi')->cascadeOnDelete();
            $table->enum('jenis_biaya', ['bahan_baku', 'bahan_penolong', 'tenaga_kerja', 'overhead']);
            $table->string('keterangan');
            $table->decimal('nominal', 12, 2);
            $table->timestamps();
        });

        // 4. Produksi Bahan Pakai
        Schema::create('produksi_bahan_pakai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksi')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->cascadeOnDelete();
            $table->decimal('kuantitas_pakai', 10, 2);
            $table->decimal('harga_satuan_saat_itu', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_bahan_pakai');
        Schema::dropIfExists('produksi_biaya');
        Schema::dropIfExists('produksi');
        Schema::dropIfExists('bill_of_materials');
    }
};
