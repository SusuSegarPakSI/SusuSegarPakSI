@extends('layouts.app')

@section('title', 'Tambah Bahan Baku')
@section('breadcrumb', 'Tambah Bahan Baku')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('master.index', ['tab' => 'bahan']) }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Tambah Bahan Baku</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kode bahan akan dibuat otomatis oleh sistem.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('master.bahan-baku.store') }}">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ── Kolom Kiri ── --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Bahan Baku</h3>
                    </div>
                    <div class="px-6 py-5 space-y-4">

                        {{-- Kode auto --}}
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kode Bahan</label>
                            <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" /></svg>
                                <span class="text-sm font-mono text-slate-500 dark:text-slate-400">Dibuat otomatis (BHN-XXXXX)</span>
                            </div>
                        </div>

                        {{-- Nama Bahan --}}
                        <div class="space-y-1.5">
                            <label for="nama_bahan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Bahan <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_bahan" name="nama_bahan" value="{{ old('nama_bahan') }}" required
                                   placeholder="Contoh: Susu Sapi Segar"
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('nama_bahan') border-red-400 @enderror">
                            @error('nama_bahan')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Grid: Kategori & Satuan --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1.5">
                                <label for="kategori" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kategori <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select id="kategori" name="kategori" required
                                            class="block w-full appearance-none px-3 py-2 pr-8 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('kategori') border-red-400 @enderror">
                                        <option value="" disabled {{ !old('kategori') ? 'selected' : '' }}>Pilih kategori...</option>
                                        <option value="Baku" {{ old('kategori') === 'Baku' ? 'selected' : '' }}>Baku</option>
                                        <option value="Penolong" {{ old('kategori') === 'Penolong' ? 'selected' : '' }}>Penolong</option>
                                        <option value="Kemasan" {{ old('kategori') === 'Kemasan' ? 'selected' : '' }}>Kemasan</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                    </div>
                                </div>
                                @error('kategori')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label for="satuan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Satuan <span class="text-red-500">*</span></label>
                                <input type="text" id="satuan" name="satuan" value="{{ old('satuan') }}" required
                                       placeholder="Liter, kg, pcs..."
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('satuan') border-red-400 @enderror">
                                @error('satuan')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Supplier Utama --}}
                        <div class="space-y-1.5">
                            <label for="supplier_utama_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Supplier Utama</label>
                            <div class="relative">
                                <select id="supplier_utama_id" name="supplier_utama_id"
                                        class="block w-full appearance-none px-3 py-2 pr-8 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                    <option value="">— Tidak ada supplier utama —</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_utama_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama_supplier }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- System managed fields --}}
                        <div class="space-y-3 pt-2">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Dikelola Sistem Otomatis</p>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="flex items-center gap-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    <div>
                                        <p class="text-[11px] text-slate-400">Harga Rata-rata (MA)</p>
                                        <p class="text-xs text-slate-500">Diperbarui oleh modul Pembelian</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                                    <div>
                                        <p class="text-[11px] text-slate-400">Stok</p>
                                        <p class="text-xs text-slate-500">Dikelola modul Persediaan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="space-y-5">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Pengaturan</h3>
                    </div>
                    <div class="px-5 py-4 space-y-4">
                        <div class="space-y-1.5">
                            <label for="stok_minimum" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Stok Minimum <span class="text-red-500">*</span></label>
                            <input type="number" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', 0) }}" min="0" required
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors font-mono @error('stok_minimum') border-red-400 @enderror">
                            @error('stok_minimum')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div x-data="{ enabled: true }">
                            <input type="hidden" name="is_active" :value="enabled ? '1' : '0'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Status Aktif</p>
                                    <p class="text-xs text-slate-400" x-text="enabled ? 'Bahan aktif digunakan' : 'Bahan tidak aktif'"></p>
                                </div>
                                <button type="button" @click="enabled = !enabled"
                                        :class="enabled ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700'"
                                        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                                    <span :class="enabled ? 'translate-x-4' : 'translate-x-0'"
                                          class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('master.index', ['tab' => 'bahan']) }}" class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg transition-colors">Batal</a>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        Simpan Bahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
