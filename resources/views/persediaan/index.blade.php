@extends('layouts.app')

@section('title', 'Persediaan & Inventory')
@section('breadcrumb', 'Persediaan')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ $tab }}' }">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Persediaan & Inventory</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau stok produk jadi, bahan baku, penolong, mutasi kartu stok, dan adjustment persediaan.</p>
        </div>
        @role('admin')
        <div class="shrink-0 flex gap-2.5">
            <a href="{{ route('persediaan.adjustment') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Adjustment Manual
            </a>
        </div>
        @endrole
    </div>

    <!-- Alert Banner for Low Stock -->
    @if ($totalKritis > 0)
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-xl flex items-center justify-between text-red-800 dark:text-red-400">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-650 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-bold">{{ $totalKritis }} item membutuhkan perhatian</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 ml-1.5">— stok saat ini berada di bawah batas stok minimum.</span>
                </div>
            </div>
            <button type="button" @click="activeTab = 'kritis'" 
                    class="text-xs font-bold text-red-750 dark:text-red-300 hover:underline">
                Lihat Tab Stok Kritis &rarr;
            </button>
        </div>
    @endif

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- SKU Produk -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total SKU Produk Jadi</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-white font-mono">{{ number_format($totalSkuProduk) }}</p>
            <span class="text-[10px] text-slate-400">Barang siap dipasarkan</span>
        </div>

        <!-- SKU Bahan Baku -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total SKU Bahan Baku</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-white font-mono">{{ number_format($totalSkuBahan) }}</p>
            <span class="text-[10px] text-slate-400">Baku, Penolong & Kemasan</span>
        </div>

        <!-- Nilai Aset Produk -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nilai Aset Produk</p>
            <p class="mt-2 text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 font-mono">{{ formatRupiah($nilaiAsetProduk) }}</p>
            <span class="text-[10px] text-slate-400">Stok &times; Harga Pokok</span>
        </div>

        <!-- Nilai Aset Bahan Baku -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nilai Aset Bahan Baku</p>
            <p class="mt-2 text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 font-mono">{{ formatRupiah($nilaiAsetBahan) }}</p>
            <span class="text-[10px] text-slate-400">Stok &times; Harga Rata-rata</span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="flex space-x-6" aria-label="Tabs">
            <button type="button" @click="activeTab = 'produk'"
                    :class="activeTab === 'produk' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Produk Jadi
            </button>
            <button type="button" @click="activeTab = 'bahan'"
                    :class="activeTab === 'bahan' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Bahan Baku & Penolong
            </button>
            <button type="button" @click="activeTab = 'kritis'"
                    :class="activeTab === 'kritis' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all flex items-center gap-1.5">
                Stok Kritis
                @if ($totalKritis > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 border border-red-200/55 dark:border-red-900/55">{{ $totalKritis }}</span>
                @endif
            </button>
            <button type="button" @click="activeTab = 'riwayat'"
                    :class="activeTab === 'riwayat' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Riwayat Adjustment
            </button>
        </nav>
    </div>

    <!-- TABS PANELS CONTENT -->
    <div>
        <!-- TAB 1: PRODUK JADI -->
        <div x-show="activeTab === 'produk'" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-center">Stok Min</th>
                            <th class="px-6 py-4 text-right">Nilai Aset</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        @foreach ($produks as $p)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors {{ $p->status_stok === 'Kritis' ? 'bg-red-50/15 dark:bg-red-950/5' : '' }}">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-900 dark:text-white">{{ $p->kode_produk }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $p->nama_produk }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $p->kategori ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-mono font-bold">{{ number_format($p->stok) }} <span class="text-xs text-slate-400 font-sans ml-0.5">{{ $p->satuan }}</span></td>
                                <td class="px-6 py-4 text-center font-mono text-slate-400">{{ number_format($p->stok_minimum) }}</td>
                                <td class="px-6 py-4 text-right font-mono font-semibold text-slate-900 dark:text-white">{{ formatRupiah($p->nilai_aset) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($p->status_stok === 'Aman')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">Aman</span>
                                    @elseif ($p->status_stok === 'Hampir Habis')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">Hampir Habis</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-950/20 text-red-750 dark:text-red-400 border border-red-100 dark:border-red-900/30">Kritis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('persediaan.kartu_stok', ['type' => 'produk', 'id' => $p->id]) }}"
                                           class="px-2.5 py-1 text-xs font-medium border border-slate-350 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg shadow-sm transition-colors">
                                            Kartu Stok
                                        </a>
                                        @role('admin')
                                        <a href="{{ route('persediaan.adjustment', ['type' => 'produk', 'item_id' => $p->id]) }}"
                                           class="px-2.5 py-1 text-xs font-semibold border border-indigo-200 hover:border-indigo-300 text-indigo-700 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 rounded-lg shadow-sm transition-colors">
                                            Adjust
                                        </a>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: BAHAN BAKU & PENOLONG -->
        <div x-show="activeTab === 'bahan'" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm" style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Bahan</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-center">Stok Min</th>
                            <th class="px-6 py-4 text-right">Nilai Aset</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        @foreach ($bahanBakus as $b)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors {{ $b->status_stok === 'Kritis' ? 'bg-red-50/15 dark:bg-red-950/5' : '' }}">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-900 dark:text-white">{{ $b->kode_bahan }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $b->nama_bahan }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $b->kategori }}</td>
                                <td class="px-6 py-4 text-center font-mono font-bold">{{ number_format($b->stok) }} <span class="text-xs text-slate-400 font-sans ml-0.5">{{ $b->satuan }}</span></td>
                                <td class="px-6 py-4 text-center font-mono text-slate-400">{{ number_format($b->stok_minimum) }}</td>
                                <td class="px-6 py-4 text-right font-mono font-semibold text-slate-900 dark:text-white">{{ formatRupiah($b->nilai_aset) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($b->status_stok === 'Aman')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">Aman</span>
                                    @elseif ($b->status_stok === 'Hampir Habis')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">Hampir Habis</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-950/20 text-red-750 dark:text-red-400 border border-red-100 dark:border-red-900/30">Kritis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('persediaan.kartu_stok', ['type' => 'bahan_baku', 'id' => $b->id]) }}"
                                           class="px-2.5 py-1 text-xs font-medium border border-slate-350 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg shadow-sm transition-colors">
                                            Kartu Stok
                                        </a>
                                        @role('admin')
                                        <a href="{{ route('persediaan.adjustment', ['type' => 'bahan_baku', 'item_id' => $b->id]) }}"
                                           class="px-2.5 py-1 text-xs font-semibold border border-indigo-200 hover:border-indigo-300 text-indigo-700 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 rounded-lg shadow-sm transition-colors">
                                            Adjust
                                        </a>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: STOK KRITIS -->
        <div x-show="activeTab === 'kritis'" class="space-y-6" style="display: none;">
            @if ($totalKritis === 0)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-12 flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-emerald-55/10 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-500 mb-3 border border-emerald-100">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Stok Aman! Semua SKU Terpenuhi</p>
                    <p class="text-xs text-slate-500 mt-0.5">Tidak ada item persediaan yang berada di bawah batas minimum.</p>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-red-50/10 dark:bg-red-950/5">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Daftar Item Kritis</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Seluruh persediaan yang berada di bawah batas stok minimum.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Tipe</th>
                                    <th class="px-6 py-4">Kode</th>
                                    <th class="px-6 py-4">Nama Item</th>
                                    <th class="px-6 py-4 text-center">Stok Saat Ini</th>
                                    <th class="px-6 py-4 text-center">Stok Minimum</th>
                                    <th class="px-6 py-4">Supplier Utama</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                                <!-- Kritis Produk -->
                                @foreach ($kritisProduks as $kp)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors bg-red-50/15 dark:bg-red-950/5">
                                        <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30">Produk Jadi</span></td>
                                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-900 dark:text-white">{{ $kp->kode_produk }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $kp->nama_produk }}</td>
                                        <td class="px-6 py-4 text-center font-mono font-bold text-red-600 dark:text-red-400">{{ number_format($kp->stok) }} {{ $kp->satuan }}</td>
                                        <td class="px-6 py-4 text-center font-mono text-slate-400">{{ number_format($kp->stok_minimum) }}</td>
                                        <td class="px-6 py-4 text-slate-400 italic text-xs">Penjualan POS / Jadi</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('persediaan.kartu_stok', ['type' => 'produk', 'id' => $kp->id]) }}"
                                                   class="px-2 py-0.5 text-xs font-medium border border-slate-350 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded shadow-sm">
                                                    Kartu Stok
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Kritis Bahan Baku -->
                                @foreach ($kritisBahans as $kb)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors bg-red-50/15 dark:bg-red-950/5">
                                        <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400 border border-teal-100 dark:border-teal-900/30">Bahan Baku</span></td>
                                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-900 dark:text-white">{{ $kb->kode_bahan }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $kb->nama_bahan }}</td>
                                        <td class="px-6 py-4 text-center font-mono font-bold text-red-600 dark:text-red-400">{{ number_format($kb->stok) }} {{ $kb->satuan }}</td>
                                        <td class="px-6 py-4 text-center font-mono text-slate-400">{{ number_format($kb->stok_minimum) }}</td>
                                        <td class="px-6 py-4">
                                            @if ($kb->supplierUtama)
                                                <span class="font-bold text-slate-900 dark:text-white">{{ $kb->supplierUtama->nama_supplier }}</span>
                                                <span class="text-[10px] text-slate-400 block font-mono">{{ $kb->supplierUtama->kode_supplier }}</span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('persediaan.kartu_stok', ['type' => 'bahan_baku', 'id' => $kb->id]) }}"
                                                   class="px-2 py-0.5 text-xs font-medium border border-slate-350 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded shadow-sm">
                                                    Kartu Stok
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- TAB 4: RIWAYAT ADJUSTMENT -->
        <div x-show="activeTab === 'riwayat'" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm" style="display: none;">
            @if ($adjustments->isEmpty())
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <p class="text-sm font-medium text-slate-750 dark:text-slate-300">Belum ada riwayat adjustment stok.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Item</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4 text-center">Stok Sistem</th>
                                <th class="px-6 py-4 text-center">Stok Fisik</th>
                                <th class="px-6 py-4 text-center">Selisih</th>
                                <th class="px-6 py-4">Alasan</th>
                                <th class="px-6 py-4">Dibuat Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                            @foreach ($adjustments as $adj)
                                @php
                                    $target = $adj->item;
                                @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors">
                                    <td class="px-6 py-4">{{ $adj->tanggal->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($target)
                                            <p class="font-bold text-slate-900 dark:text-white">{{ $target->nama_produk ?? $target->nama_bahan }}</p>
                                            <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $target->kode_produk ?? $target->kode_bahan }}</p>
                                        @else
                                            <span class="text-slate-450 italic">Item Terhapus (ID: {{ $adj->item_id }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($adj->item_type === 'produk')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30">Produk Jadi</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400 border border-teal-100 dark:border-teal-900/30">Bahan Baku</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono">{{ number_format($adj->stok_sistem) }}</td>
                                    <td class="px-6 py-4 text-center font-mono">{{ number_format($adj->stok_fisik) }}</td>
                                    <td class="px-6 py-4 text-center font-mono font-bold">
                                        @if ($adj->selisih > 0)
                                            <span class="text-emerald-600 dark:text-emerald-400">+{{ number_format($adj->selisih) }}</span>
                                        @elseif ($adj->selisih < 0)
                                            <span class="text-red-600 dark:text-red-400">{{ number_format($adj->selisih) }}</span>
                                        @else
                                            <span class="text-slate-450">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate" title="{{ $adj->alasan }}">{{ $adj->alasan }}</td>
                                    <td class="px-6 py-4 text-xs font-medium text-slate-900 dark:text-white">{{ $adj->creator->name ?? 'Sistem' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
