@extends('layouts.app')

@section('title', 'Tambah Pembelian Bahan Baku')
@section('breadcrumb', 'Pembelian / Tambah')

@section('content')
<div class="space-y-6" x-data="pembelianForm()">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('pembelian.index') }}" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Tambah Nota Pembelian</h1>
            <p class="text-sm text-slate-500 mt-0.5">Catat nota pembelian bahan baku dari supplier baru untuk memperbarui stok dan moving average.</p>
        </div>
    </div>

    <!-- Errors -->
    @if ($errors->any())
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-xl text-red-800 dark:text-red-400">
            <div class="flex items-center gap-2 font-semibold text-sm mb-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                </svg>
                Ada kesalahan input form:
            </div>
            <ul class="list-disc pl-5 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="purchase-form" action="{{ route('pembelian.store') }}" method="POST" @submit.prevent="showConfirmModal = true">
        @csrf
        
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- HEADER NOTA (Kiri 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header Info Card -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Header Nota</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Informasi dasar transaksi pembelian.</p>
                    </div>

                    <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Supplier Searchable Selector -->
                        <div class="space-y-1.5" x-data="{ open: false, search: '' }">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Pilih Supplier <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <button type="button" @click="open = !open"
                                        class="block w-full text-left px-3 py-2 pr-10 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                    <span x-text="selectedSupplierName ? selectedSupplierName : 'Pilih supplier...'">Pilih supplier...</span>
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                    </span>
                                </button>
                                <input type="hidden" name="supplier_id" :value="selectedSupplierId">

                                <div x-show="open" @click.away="open = false"
                                     class="absolute left-0 mt-1 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg z-50 overflow-hidden">
                                    <div class="p-2 border-b border-slate-100 dark:border-slate-700">
                                        <input type="text" x-model="search" placeholder="Cari supplier..."
                                               class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                    <ul class="max-h-52 overflow-y-auto p-1.5 space-y-0.5">
                                        <template x-for="supplier in filterSuppliers(search)" :key="supplier.id">
                                            <li>
                                                <button type="button" @click="selectSupplier(supplier.id, supplier.name); open = false"
                                                        class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-slate-700 dark:text-slate-200 flex items-center justify-between">
                                                    <span class="font-medium" x-text="supplier.name"></span>
                                                    <span class="font-mono text-slate-400" x-text="supplier.kode"></span>
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Nomor Faktur Supplier -->
                        <div class="space-y-1.5">
                            <label for="nomor_faktur" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Nomor Faktur Supplier <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nomor_faktur" name="nomor_faktur" required placeholder="Masukkan nomor faktur dari nota supplier"
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                        </div>

                        <!-- Nomor PO (optional) -->
                        <div class="space-y-1.5">
                            <label for="nomor_po" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Nomor Purchase Order (PO) <span class="text-xs text-slate-400">(Opsional)</span>
                            </label>
                            <input type="text" id="nomor_po" name="nomor_po" placeholder="Contoh: PO-00123"
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                        </div>

                        <!-- Tanggal Faktur -->
                        <div class="space-y-1.5">
                            <label for="tanggal_faktur" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Tanggal Faktur <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal_faktur" name="tanggal_faktur" required x-model="tanggalFaktur"
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                        </div>
                    </div>
                </div>

                <!-- DETAIL TABEL DINAMIS (ALPINE) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Daftar Bahan Baku</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar item bahan baku yang dibeli.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                    <th class="px-4 py-3 w-7/12">Bahan Baku</th>
                                    <th class="px-4 py-3 text-center w-2/12">Qty</th>
                                    <th class="px-4 py-3 text-right w-2/12">Harga Satuan</th>
                                    <th class="px-4 py-3 text-right w-2/12">Subtotal</th>
                                    <th class="px-4 py-3 text-center w-1/12">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700">
                                <template x-for="(row, index) in rows" :key="index">
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors">
                                        <td class="px-4 py-3 relative" x-data="{ openBahan: false, searchBahan: '' }">
                                            <!-- Bahan Baku Select -->
                                            <button type="button" @click="openBahan = !openBahan"
                                                    class="w-full text-left px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors flex items-center justify-between">
                                                <span x-text="row.bahanName ? row.bahanName : 'Pilih bahan baku...'">Pilih bahan baku...</span>
                                                <span class="text-slate-400">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                                </span>
                                            </button>
                                            <input type="hidden" :name="`items[${index}][bahan_baku_id]`" :value="row.bahanId">

                                            <!-- Dropdown Options -->
                                            <div x-show="openBahan" @click.away="openBahan = false"
                                                 class="absolute left-4 right-4 mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg z-50 overflow-hidden">
                                                <div class="p-1.5 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                                                    <input type="text" x-model="searchBahan" placeholder="Cari bahan..."
                                                           class="w-full px-2.5 py-1 text-[11px] bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-850 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                </div>
                                                <ul class="max-h-40 overflow-y-auto p-1 space-y-0.5">
                                                    <template x-for="b in filterBahan(searchBahan)" :key="b.id">
                                                        <li>
                                                            <button type="button" @click="selectBahan(index, b.id, b.name, b.satuan, b.harga); openBahan = false"
                                                                    class="w-full text-left px-2 py-1 rounded-lg text-xs hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-slate-700 dark:text-slate-200 flex items-center justify-between">
                                                                <div>
                                                                    <span class="font-medium" x-text="b.name"></span>
                                                                    <span class="text-[10px] text-slate-400 ml-1.5" x-text="`(${b.satuan})`"></span>
                                                                </div>
                                                                <span class="font-mono text-[10px] text-slate-500" x-text="formatRupiah(b.harga)"></span>
                                                            </button>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <!-- Qty -->
                                            <div class="flex items-center justify-center gap-1.5">
                                                <input type="number" :name="`items[${index}][kuantitas]`" required min="1" x-model.number="row.qty"
                                                       class="w-16 px-2 py-1 text-center text-xs font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                <span class="text-xs text-slate-400" x-text="row.satuan"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <!-- Harga Satuan -->
                                            <div class="relative inline-block w-full">
                                                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-slate-400 text-xs">Rp</span>
                                                <input type="number" :name="`items[${index}][harga_satuan]`" required min="0" x-model.number="row.harga"
                                                       class="w-full pl-8 pr-2.5 py-1 text-right text-xs font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-semibold text-slate-900 dark:text-white text-xs">
                                            <span x-text="formatRupiah(row.qty * row.harga)"></span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <!-- Hapus button -->
                                            <button type="button" @click="removeRow(index)" :disabled="rows.length <= 1"
                                                    class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 disabled:opacity-40 disabled:cursor-not-allowed rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 dark:bg-slate-900/20 border-t border-slate-200 dark:border-slate-800">
                                    <td colspan="3" class="px-4 py-3.5 text-xs font-semibold text-slate-700 dark:text-slate-300 text-right uppercase">Total Pembelian</td>
                                    <td class="px-4 py-3.5 text-right font-mono font-extrabold text-indigo-600 dark:text-indigo-400 text-sm">
                                        <span x-text="formatRupiah(calculateTotal())"></span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/20 flex justify-start">
                        <button type="button" @click="addRow()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold border border-slate-350 hover:border-slate-400 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shadow-sm focus:outline-none">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            + Tambah Bahan
                        </button>
                    </div>
                </div>
            </div>

            <!-- DETAIL PEMBAYARAN & SUBMIT (Kanan 1/3) -->
            <div class="space-y-6">
                <!-- Payment Status Panel -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-6 space-y-5">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">Informasi Pembayaran</h3>

                    <!-- Status Bayar -->
                    <div class="space-y-2">
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Pembayaran</span>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="status_bayar" value="Lunas" x-model="statusBayar"
                                       class="w-4 h-4 border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-850 cursor-pointer">
                                <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">Lunas</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="status_bayar" value="Belum Lunas" x-model="statusBayar"
                                       class="w-4 h-4 border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-850 cursor-pointer">
                                <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">Belum Lunas</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tanggal Jatuh Tempo (Shown if Belum Lunas) -->
                    <div class="space-y-1.5" x-show="statusBayar === 'Belum Lunas'" x-transition>
                        <label for="tanggal_jatuh_tempo" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" :required="statusBayar === 'Belum Lunas'"
                               class="block w-full px-3 py-2 text-xs bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                    </div>

                    <!-- Catatan -->
                    <div class="space-y-1.5">
                        <label for="catatan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Catatan / Keterangan
                        </label>
                        <textarea id="catatan" name="catatan" rows="3" placeholder="Tulis keterangan tambahan..."
                                  class="block w-full px-3 py-2 text-xs bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"></textarea>
                    </div>
                </div>

                <!-- Submit Card -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-6 space-y-4">
                    <div class="space-y-1 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs text-slate-400">Total Nominal Pembelian</span>
                        <div class="text-2xl font-mono font-extrabold text-slate-900 dark:text-white">
                            <span x-text="formatRupiah(calculateTotal())"></span>
                        </div>
                    </div>

                    <button type="submit" :disabled="!selectedSupplierId || rows.length === 0 || calculateTotal() <= 0"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        Simpan & Terima Stok
                    </button>
                    <a href="{{ route('pembelian.index') }}"
                       class="w-full block text-center px-4 py-2 text-sm font-semibold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- CONFIRMATION MODAL -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showConfirmModal = false"></div>

        <!-- Position Wrapper -->
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-slate-900 text-left shadow-xl border border-slate-200 dark:border-slate-800 transition-all sm:my-8 sm:w-full sm:max-w-md p-6"
                 x-trap="showConfirmModal">
                
                <div class="flex items-start gap-4">
                    <div class="mx-auto flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Konfirmasi Simpan Pembelian</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Apakah Anda yakin ingin menyimpan nota pembelian ini? Tindakan ini akan langsung memperbarui:
                        </p>
                        <ul class="list-disc pl-5 mt-2 text-xs text-slate-500 space-y-1.5">
                            <li>Stok aktual bahan baku akan ditambahkan.</li>
                            <li>Harga rata-rata (Moving Average) akan dihitung ulang secara otomatis.</li>
                            <li x-show="statusBayar === 'Belum Lunas'">Saldo hutang supplier <span class="font-semibold text-slate-700 dark:text-slate-350" x-text="selectedSupplierName"></span> akan bertambah sebesar <span class="font-semibold text-slate-700 dark:text-slate-350 font-mono" x-text="formatRupiah(calculateTotal())"></span>.</li>
                            <li x-show="statusBayar === 'Lunas'">Nota langsung ditandai Lunas, tidak ada penambahan hutang supplier.</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button type="button" @click="showConfirmModal = false"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="button" @click="submitPurchase()"
                            class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Ya, Simpan & Terima Stok
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function pembelianForm() {
        return {
            selectedSupplierId: '',
            selectedSupplierName: '',
            tanggalFaktur: '{{ Carbon\Carbon::now()->format("Y-m-d") }}',
            statusBayar: 'Belum Lunas',
            showConfirmModal: false,
            rows: [
                { bahanId: '', bahanName: '', satuan: '', qty: 1, harga: 0 }
            ],

            suppliers: [
                @foreach ($suppliers as $s)
                    { id: '{{ $s->id }}', name: '{{ $s->nama_supplier }}', kode: '{{ $s->kode_supplier }}' },
                @endforeach
            ],

            bahanBakus: [
                @foreach ($bahanBakus as $b)
                    { id: '{{ $b->id }}', name: '{{ $b->nama_bahan }}', satuan: '{{ $b->satuan }}', harga: {{ $b->harga_rata_rata }} },
                @endforeach
            ],

            filterSuppliers(search) {
                if (!search) return this.suppliers;
                return this.suppliers.filter(s => 
                    s.name.toLowerCase().includes(search.toLowerCase()) || 
                    s.kode.toLowerCase().includes(search.toLowerCase())
                );
            },

            filterBahan(search) {
                if (!search) return this.bahanBakus;
                return this.bahanBakus.filter(b => 
                    b.name.toLowerCase().includes(search.toLowerCase())
                );
            },

            selectSupplier(id, name) {
                this.selectedSupplierId = id;
                this.selectedSupplierName = name;
            },

            selectBahan(index, id, name, satuan, harga) {
                this.rows[index].bahanId = id;
                this.rows[index].bahanName = name;
                this.rows[index].satuan = satuan;
                this.rows[index].harga = harga;
            },

            addRow() {
                this.rows.push({ bahanId: '', bahanName: '', satuan: '', qty: 1, harga: 0 });
            },

            removeRow(index) {
                if (this.rows.length > 1) {
                    this.rows.splice(index, 1);
                }
            },

            calculateTotal() {
                return this.rows.reduce((sum, row) => sum + (row.qty * row.harga), 0);
            },

            formatRupiah(value) {
                return 'Rp ' + number_format(value, 0, ',', '.');
            },

            submitPurchase() {
                document.getElementById('purchase-form').submit();
            }
        };
    }

    // Helper number_format like PHP
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k).toFixed(prec);
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
@endsection
