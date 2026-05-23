@extends('layouts.app')

@section('title', 'Master Data')
@section('breadcrumb', 'Master Data')

@section('content')
<div x-data="{
    activeTab: '{{ request('tab', 'produk') }}',
    searchProduk: '',
    searchBahan: '',
    searchPelanggan: '',
    searchSupplier: '',
    filterProduk: 'semua',
    filterBahan: 'semua',
    filterPelanggan: 'semua',
    filterSupplier: 'semua',
    openToggle: false,
    toggleItem: { id: '', name: '', active: false, url: '' },
    setTab(tab) {
        this.activeTab = tab;
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    }
}" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Master Data</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kelola data referensi: Produk, Bahan Baku, Pelanggan, dan Supplier.</p>
        </div>
        @role('admin')
        <div class="flex items-center gap-2">
            <a x-show="activeTab === 'produk'" href="{{ route('master.produk.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Produk
            </a>
            <a x-show="activeTab === 'bahan'" href="{{ route('master.bahan-baku.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Bahan Baku
            </a>
            <a x-show="activeTab === 'pelanggan'" href="{{ route('master.pelanggan.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Pelanggan
            </a>
            <a x-show="activeTab === 'supplier'" href="{{ route('master.supplier.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Supplier
            </a>
        </div>
        @endrole
    </div>

    {{-- Tabs --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="border-b border-slate-200 dark:border-slate-800">
            <nav class="-mb-px flex overflow-x-auto" aria-label="Tabs">
                <button @click="setTab('produk')"
                        :class="activeTab === 'produk' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600'"
                        class="flex items-center gap-2 whitespace-nowrap border-b-2 px-5 py-3.5 text-sm font-medium transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                    Produk
                    <span class="ml-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[11px] font-mono px-1.5 py-0.5 rounded-full">{{ $produks->total() }}</span>
                </button>
                <button @click="setTab('bahan')"
                        :class="activeTab === 'bahan' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600'"
                        class="flex items-center gap-2 whitespace-nowrap border-b-2 px-5 py-3.5 text-sm font-medium transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>
                    Bahan Baku
                    <span class="ml-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[11px] font-mono px-1.5 py-0.5 rounded-full">{{ $bahanBakus->total() }}</span>
                </button>
                <button @click="setTab('pelanggan')"
                        :class="activeTab === 'pelanggan' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600'"
                        class="flex items-center gap-2 whitespace-nowrap border-b-2 px-5 py-3.5 text-sm font-medium transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    Pelanggan
                    <span class="ml-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[11px] font-mono px-1.5 py-0.5 rounded-full">{{ $customers->total() }}</span>
                </button>
                <button @click="setTab('supplier')"
                        :class="activeTab === 'supplier' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600'"
                        class="flex items-center gap-2 whitespace-nowrap border-b-2 px-5 py-3.5 text-sm font-medium transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m2.25 0h1.5a1.125 1.125 0 0 0 1.125-1.125V11.25m-19.5 0h19.5L16.666 4.675A2.25 2.25 0 0 0 14.772 3.75H7.228a2.25 2.25 0 0 0-1.894.925L2.25 11.25Z" /></svg>
                    Supplier
                    <span class="ml-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[11px] font-mono px-1.5 py-0.5 rounded-full">{{ $suppliers->total() }}</span>
                </button>
            </nav>
        </div>

        {{-- ═══════ TAB: PRODUK ═══════ --}}
        <div x-show="activeTab === 'produk'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            {{-- Toolbar --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="relative flex-1 max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </div>
                    <input type="search" x-model="searchProduk" placeholder="Cari produk..." class="block w-full pl-9 pr-4 py-1.5 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Filter:</span>
                    <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                        <button @click="filterProduk = 'semua'" :class="filterProduk === 'semua' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium transition-colors">Semua</button>
                        <button @click="filterProduk = 'aktif'" :class="filterProduk === 'aktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Aktif</button>
                        <button @click="filterProduk = 'nonaktif'" :class="filterProduk === 'nonaktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Nonaktif</button>
                    </div>
                </div>
            </div>

            {{-- Table Produk --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Produk</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kategori / Satuan</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Harga Jual</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Stok</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($produks as $produk)
                            <tr x-show="
                                (filterProduk === 'semua' || (filterProduk === 'aktif' && {{ $produk->is_active ? 'true' : 'false' }}) || (filterProduk === 'nonaktif' && {{ !$produk->is_active ? 'true' : 'false' }}))
                                && (searchProduk === '' || '{{ strtolower($produk->nama_produk) }}'.includes(searchProduk.toLowerCase()) || '{{ strtolower($produk->kode_produk) }}'.includes(searchProduk.toLowerCase()))
                            " class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if ($produk->foto)
                                            <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama_produk }}" class="w-9 h-9 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-white">{{ $produk->nama_produk }}</p>
                                            @if($produk->deskripsi)
                                                <p class="text-xs text-slate-400 dark:text-slate-500 truncate max-w-[180px]">{{ $produk->deskripsi }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $produk->kode_produk }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-slate-700 dark:text-slate-300">{{ $produk->kategori ?: '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $produk->satuan }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="font-mono text-sm font-medium text-slate-800 dark:text-slate-200">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-mono text-sm {{ $produk->stok <= $produk->stok_minimum ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ number_format($produk->stok) }}
                                    </span>
                                    @if ($produk->stok <= $produk->stok_minimum)
                                        <p class="text-[10px] text-red-500 dark:text-red-400">Stok rendah</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($produk->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('master.produk.show', $produk) }}" class="p-2 rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-950/50 transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" /></svg>
                                        </a>
                                        @role('admin')
                                        <a href="{{ route('master.produk.edit', $produk) }}" class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                                        </a>
                                        <button @click="toggleItem = { id: '{{ $produk->id }}', name: '{{ $produk->nama_produk }}', active: {{ $produk->is_active ? 'true' : 'false' }}, url: '{{ route('master.produk.toggle', $produk) }}' }; openToggle = true"
                                                class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors" title="{{ $produk->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if ($produk->is_active)
                                                <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                            @else
                                                <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            @endif
                                        </button>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada produk</p>
                                        @role('admin')<a href="{{ route('master.produk.create') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Tambah produk pertama</a>@endrole
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($produks->hasPages())
                <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-800">{{ $produks->links() }}</div>
            @endif
        </div>

        {{-- ═══════ TAB: BAHAN BAKU ═══════ --}}
        <div x-show="activeTab === 'bahan'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none">
            {{-- Toolbar --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="relative flex-1 max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </div>
                    <input type="search" x-model="searchBahan" placeholder="Cari bahan baku..." class="block w-full pl-9 pr-4 py-1.5 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Filter:</span>
                    <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                        <button @click="filterBahan = 'semua'" :class="filterBahan === 'semua' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium transition-colors">Semua</button>
                        <button @click="filterBahan = 'aktif'" :class="filterBahan === 'aktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Aktif</button>
                        <button @click="filterBahan = 'nonaktif'" :class="filterBahan === 'nonaktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Nonaktif</button>
                    </div>
                </div>
            </div>

            {{-- Table Bahan Baku --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Bahan Baku</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Harga Rata-rata</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Stok</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($bahanBakus as $bahan)
                            <tr x-show="
                                (filterBahan === 'semua' || (filterBahan === 'aktif' && {{ $bahan->is_active ? 'true' : 'false' }}) || (filterBahan === 'nonaktif' && {{ !$bahan->is_active ? 'true' : 'false' }}))
                                && (searchBahan === '' || '{{ strtolower($bahan->nama_bahan) }}'.includes(searchBahan.toLowerCase()) || '{{ strtolower($bahan->kode_bahan) }}'.includes(searchBahan.toLowerCase()))
                            " class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $bahan->nama_bahan }}</p>
                                    <p class="text-xs text-slate-400">{{ $bahan->satuan }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $bahan->kode_bahan }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $kategoriColor = match($bahan->kategori) {
                                            'Baku' => 'text-blue-700 bg-blue-50 dark:text-blue-400 dark:bg-blue-950/40',
                                            'Penolong' => 'text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/40',
                                            'Kemasan' => 'text-violet-700 bg-violet-50 dark:text-violet-400 dark:bg-violet-950/40',
                                            default => 'text-slate-700 bg-slate-100 dark:text-slate-400 dark:bg-slate-800',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $kategoriColor }}">{{ $bahan->kategori }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="font-mono text-sm font-medium text-slate-800 dark:text-slate-200">Rp {{ number_format($bahan->harga_rata_rata, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-mono text-sm {{ $bahan->stok <= $bahan->stok_minimum ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">{{ number_format($bahan->stok) }}</span>
                                    @if ($bahan->stok <= $bahan->stok_minimum)
                                        <p class="text-[10px] text-red-500">Stok rendah</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($bahan->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('master.bahan-baku.show', $bahan) }}" class="p-2 rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-950/50 transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" /></svg>
                                        </a>
                                        @role('admin')
                                        <a href="{{ route('master.bahan-baku.edit', $bahan) }}" class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                                        </a>
                                        <button @click="toggleItem = { id: '{{ $bahan->id }}', name: '{{ $bahan->nama_bahan }}', active: {{ $bahan->is_active ? 'true' : 'false' }}, url: '{{ route('master.bahan-baku.toggle', $bahan) }}' }; openToggle = true"
                                                class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors" title="{{ $bahan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if ($bahan->is_active)
                                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                            @else
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            @endif
                                        </button>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada bahan baku</p>
                                        @role('admin')<a href="{{ route('master.bahan-baku.create') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Tambah bahan baku pertama</a>@endrole
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($bahanBakus->hasPages())
                <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-800">{{ $bahanBakus->links() }}</div>
            @endif
        </div>

        {{-- ═══════ TAB: PELANGGAN ═══════ --}}
        <div x-show="activeTab === 'pelanggan'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none">
            {{-- Toolbar --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="relative flex-1 max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </div>
                    <input type="search" x-model="searchPelanggan" placeholder="Cari pelanggan..." class="block w-full pl-9 pr-4 py-1.5 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Filter:</span>
                    <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                        <button @click="filterPelanggan = 'semua'" :class="filterPelanggan === 'semua' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium transition-colors">Semua</button>
                        <button @click="filterPelanggan = 'aktif'" :class="filterPelanggan === 'aktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Aktif</button>
                        <button @click="filterPelanggan = 'nonaktif'" :class="filterPelanggan === 'nonaktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Nonaktif</button>
                    </div>
                </div>
            </div>

            {{-- Table Pelanggan --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Tipe</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kontak</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Saldo Piutang</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($customers as $customer)
                            <tr x-show="
                                (filterPelanggan === 'semua' || (filterPelanggan === 'aktif' && {{ $customer->is_active ? 'true' : 'false' }}) || (filterPelanggan === 'nonaktif' && {{ !$customer->is_active ? 'true' : 'false' }}))
                                && (searchPelanggan === '' || '{{ strtolower($customer->nama) }}'.includes(searchPelanggan.toLowerCase()) || '{{ strtolower($customer->kode_pelanggan) }}'.includes(searchPelanggan.toLowerCase()))
                            " class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ strtoupper(substr($customer->nama, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-white">{{ $customer->nama }}</p>
                                            @if ($customer->alamat)
                                                <p class="text-xs text-slate-400 truncate max-w-[180px]">{{ $customer->alamat }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $customer->kode_pelanggan }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $customer->tipe === 'Mitra' ? 'text-indigo-700 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-950/40' : 'text-slate-700 bg-slate-100 dark:text-slate-400 dark:bg-slate-800' }}">{{ $customer->tipe }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300 font-mono text-xs">{{ $customer->telepon ?: '-' }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="font-mono text-sm {{ $customer->saldo_piutang > 0 ? 'text-amber-700 dark:text-amber-400 font-semibold' : 'text-slate-500 dark:text-slate-400' }}">
                                        Rp {{ number_format($customer->saldo_piutang, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($customer->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('master.pelanggan.show', $customer) }}" class="p-2 rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-950/50 transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" /></svg>
                                        </a>
                                        @role('admin')
                                        <a href="{{ route('master.pelanggan.edit', $customer) }}" class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                                        </a>
                                        <button @click="toggleItem = { id: '{{ $customer->id }}', name: '{{ $customer->nama }}', active: {{ $customer->is_active ? 'true' : 'false' }}, url: '{{ route('master.pelanggan.toggle', $customer) }}' }; openToggle = true"
                                                class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-105 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="{{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if ($customer->is_active)
                                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                            @else
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            @endif
                                        </button>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada pelanggan</p>
                                        @role('admin')<a href="{{ route('master.pelanggan.create') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Tambah pelanggan pertama</a>@endrole
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($customers->hasPages())
                <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-800">{{ $customers->links() }}</div>
            @endif
        </div>

        {{-- ═══════ TAB: SUPPLIER ═══════ --}}
        <div x-show="activeTab === 'supplier'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none">
            {{-- Toolbar --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="relative flex-1 max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </div>
                    <input type="search" x-model="searchSupplier" placeholder="Cari supplier..." class="block w-full pl-9 pr-4 py-1.5 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Filter:</span>
                    <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                        <button @click="filterSupplier = 'semua'" :class="filterSupplier === 'semua' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium transition-colors">Semua</button>
                        <button @click="filterSupplier = 'aktif'" :class="filterSupplier === 'aktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Aktif</button>
                        <button @click="filterSupplier = 'nonaktif'" :class="filterSupplier === 'nonaktif' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-medium border-l border-slate-200 dark:border-slate-700 transition-colors">Nonaktif</button>
                    </div>
                </div>
            </div>

            {{-- Table Supplier --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Supplier</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Kontak</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Alamat</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Saldo Hutang</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($suppliers as $supplier)
                            <tr x-show="
                                (filterSupplier === 'semua' || (filterSupplier === 'aktif' && {{ $supplier->is_active ? 'true' : 'false' }}) || (filterSupplier === 'nonaktif' && {{ !$supplier->is_active ? 'true' : 'false' }}))
                                && (searchSupplier === '' || '{{ strtolower($supplier->nama_supplier) }}'.includes(searchSupplier.toLowerCase()) || '{{ strtolower($supplier->kode_supplier) }}'.includes(searchSupplier.toLowerCase()))
                            " class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $supplier->nama_supplier }}</p>
                                    @if ($supplier->nama_kontak)
                                        <p class="text-xs text-slate-400">CP: {{ $supplier->nama_kontak }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $supplier->kode_supplier }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs text-slate-600 dark:text-slate-300">{{ $supplier->telepon ?: '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 max-w-[200px]">
                                    <p class="truncate text-xs">{{ $supplier->alamat ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="font-mono text-sm {{ $supplier->saldo_hutang > 0 ? 'text-red-700 dark:text-red-400 font-semibold' : 'text-slate-500 dark:text-slate-400' }}">
                                        Rp {{ number_format($supplier->saldo_hutang, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($supplier->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('master.supplier.show', $supplier) }}" class="p-2 rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-950/50 transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" /></svg>
                                        </a>
                                        @role('admin')
                                        <a href="{{ route('master.supplier.edit', $supplier) }}" class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                                        </a>
                                        <button @click="toggleItem = { id: '{{ $supplier->id }}', name: '{{ $supplier->nama_supplier }}', active: {{ $supplier->is_active ? 'true' : 'false' }}, url: '{{ route('master.supplier.toggle', $supplier) }}' }; openToggle = true"
                                                class="p-2 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-105 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="{{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if ($supplier->is_active)
                                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                            @else
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            @endif
                                        </button>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m2.25 0h1.5a1.125 1.125 0 0 0 1.125-1.125V11.25m-19.5 0h19.5L16.666 4.675A2.25 2.25 0 0 0 14.772 3.75H7.228a2.25 2.25 0 0 0-1.894.925L2.25 11.25Z" /></svg>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada supplier</p>
                                        @role('admin')<a href="{{ route('master.supplier.create') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Tambah supplier pertama</a>@endrole
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($suppliers->hasPages())
                <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-800">{{ $suppliers->links() }}</div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- MODAL: KONFIRMASI TOGGLE STATUS             --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="openToggle" class="relative z-50" style="display:none">
        <div x-show="openToggle"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="openToggle = false"></div>

        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="openToggle"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                    <form method="POST" :action="toggleItem.url">
                        @csrf
                        @method('PATCH')
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Konfirmasi Perubahan Status</h3>
                            <button type="button" @click="openToggle = false" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="px-6 py-5">
                            <div class="flex items-start gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                     :class="toggleItem.active ? 'bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white" x-text="toggleItem.active ? 'Nonaktifkan Item?' : 'Aktifkan Kembali Item?'"></h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                        Apakah Anda yakin ingin <span x-text="toggleItem.active ? 'menonaktifkan' : 'mengaktifkan kembali'"></span>
                                        <strong class="text-slate-800 dark:text-slate-200" x-text="toggleItem.name"></strong>?
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
                            <button type="button" @click="openToggle = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors focus:outline-none">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm transition-colors focus:outline-none"
                                    :class="toggleItem.active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700'">
                                <span x-text="toggleItem.active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
