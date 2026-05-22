@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('breadcrumb', 'Tambah Produk')

@section('content')
<div class="space-y-6" x-data="{
    previewUrl: null,
    handleFile(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => this.previewUrl = e.target.result;
            reader.readAsDataURL(file);
        }
    }
}">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('master.index', ['tab' => 'produk']) }}"
           class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Tambah Produk Baru</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kode produk akan dibuat otomatis oleh sistem.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('master.produk.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ── Kolom Kiri: Informasi Utama ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Kode (readonly info) --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Produk</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Isi data utama produk.</p>
                    </div>
                    <div class="px-6 py-5 space-y-4">

                        {{-- Kode Produk - auto generated --}}
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kode Produk</label>
                            <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" /></svg>
                                <span class="text-sm font-mono text-slate-500 dark:text-slate-400">Dibuat otomatis (PRD-XXXXX)</span>
                            </div>
                        </div>

                        {{-- Nama Produk --}}
                        <div class="space-y-1.5">
                            <label for="nama_produk" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required
                                   placeholder="Contoh: Susu Segar 1 Liter"
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('nama_produk') border-red-400 focus:ring-red-400 @enderror">
                            @error('nama_produk')
                                <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Grid: Kategori & Satuan --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1.5">
                                <label for="kategori" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kategori</label>
                                <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}"
                                       placeholder="Contoh: Susu Murni, Olahan"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            </div>
                            <div class="space-y-1.5">
                                <label for="satuan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="satuan" name="satuan" value="{{ old('satuan') }}" required
                                       placeholder="Liter, kg, pcs, botol..."
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('satuan') border-red-400 focus:ring-red-400 @enderror">
                                @error('satuan')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="space-y-1.5">
                            <label for="deskripsi" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                      placeholder="Deskripsi singkat produk (opsional)"
                                      class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm resize-y focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">{{ old('deskripsi') }}</textarea>
                        </div>

                        {{-- Harga Jual --}}
                        <div class="space-y-1.5">
                            <label for="harga_jual" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Harga Jual <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Rp</span>
                                </div>
                                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', 0) }}" min="0" step="100" required
                                       class="block w-full pl-10 pr-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors font-mono @error('harga_jual') border-red-400 @enderror">
                            </div>
                            @error('harga_jual')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Info Fields (readonly - managed by other modules) --}}
                        <div class="space-y-3 pt-2">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Dikelola Sistem Otomatis</p>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="flex items-center gap-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    <div>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500">Harga Pokok</p>
                                        <p class="text-xs font-mono text-slate-500 dark:text-slate-400">Diperbarui oleh modul Produksi</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                                    <div>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500">Stok</p>
                                        <p class="text-xs font-mono text-slate-500 dark:text-slate-400">Dikelola modul Persediaan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan: Pengaturan & Foto ── --}}
            <div class="space-y-5">

                {{-- Pengaturan Stok --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Pengaturan Stok</h3>
                    </div>
                    <div class="px-5 py-4 space-y-4">
                        <div class="space-y-1.5">
                            <label for="stok_minimum" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Stok Minimum <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', 0) }}" min="0" required
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors font-mono @error('stok_minimum') border-red-400 @enderror">
                            <p class="text-xs text-slate-400 dark:text-slate-500">Sistem akan memberi peringatan saat stok di bawah nilai ini.</p>
                            @error('stok_minimum')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Status Aktif --}}
                        <div class="pt-2" x-data="{ enabled: {{ old('is_active', true) ? 'true' : 'false' }} }">
                            <input type="hidden" name="is_active" :value="enabled ? '1' : '0'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Status Aktif</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500" x-text="enabled ? 'Produk tersedia untuk transaksi' : 'Produk tidak aktif'"></p>
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

                {{-- Upload Foto --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Foto Produk</h3>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        {{-- Preview --}}
                        <div class="relative w-full aspect-square rounded-xl overflow-hidden border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex items-center justify-center cursor-pointer"
                             @click="$refs.fotoInput.click()">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Preview" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!previewUrl">
                                <div class="text-center px-4">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">Klik untuk upload foto</p>
                                    <p class="text-[11px] text-slate-300 dark:text-slate-600 mt-1">JPEG, PNG, WebP max 2MB</p>
                                </div>
                            </template>
                        </div>
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                               x-ref="fotoInput" @change="handleFile($event)" class="hidden">
                        <button type="button" @click="$refs.fotoInput.click()"
                                class="w-full py-2 text-xs font-medium text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Pilih Foto
                        </button>
                        @error('foto')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('master.index', ['tab' => 'produk']) }}"
                       class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        Simpan Produk
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
