@extends('layouts.app')

@section('title', 'Edit Produk: ' . $produk->nama_produk)
@section('breadcrumb', 'Edit Produk')

@section('content')
<div class="space-y-6" x-data="{
    previewUrl: '{{ $produk->foto ? Storage::url($produk->foto) : null }}',
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
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Produk</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi produk. Kode dan stok tidak dapat diubah dari sini.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('master.produk.update', $produk) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ── Kolom Kiri: Informasi Utama ── --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Produk</h3>
                        </div>
                        {{-- Kode Badge --}}
                        <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <span class="font-mono bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">{{ $produk->kode_produk }}</span>
                            <span class="text-[11px] text-slate-400">Kode tidak dapat diubah</span>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-4">

                        {{-- Nama Produk --}}
                        <div class="space-y-1.5">
                            <label for="nama_produk" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('nama_produk') border-red-400 focus:ring-red-400 @enderror">
                            @error('nama_produk')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Grid: Kategori & Satuan --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1.5">
                                <label for="kategori" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kategori</label>
                                <input type="text" id="kategori" name="kategori" value="{{ old('kategori', $produk->kategori) }}"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            </div>
                            <div class="space-y-1.5">
                                <label for="satuan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="satuan" name="satuan" value="{{ old('satuan', $produk->satuan) }}" required
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('satuan') border-red-400 @enderror">
                                @error('satuan')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="space-y-1.5">
                            <label for="deskripsi" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                      class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm resize-y focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
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
                                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $produk->harga_jual) }}" min="0" step="100" required
                                       class="block w-full pl-10 pr-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors font-mono @error('harga_jual') border-red-400 @enderror">
                            </div>
                            @error('harga_jual')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- System-managed readonly fields --}}
                        <div class="space-y-3 pt-2">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Diperbarui Otomatis oleh Sistem</p>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="flex items-center justify-between px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                                    <div>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500">Harga Pokok (HPP)</p>
                                        <p class="text-sm font-mono font-medium text-slate-700 dark:text-slate-300">Rp {{ number_format($produk->harga_pokok, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">Produksi</span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                                    <div>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500">Stok Saat Ini</p>
                                        <p class="text-sm font-mono font-medium {{ $produk->stok <= $produk->stok_minimum ? 'text-red-600 dark:text-red-400' : 'text-slate-700 dark:text-slate-300' }}">{{ number_format($produk->stok) }} {{ $produk->satuan }}</p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">Persediaan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="space-y-5">
                {{-- Pengaturan Stok & Status --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Pengaturan</h3>
                    </div>
                    <div class="px-5 py-4 space-y-4">
                        <div class="space-y-1.5">
                            <label for="stok_minimum" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Stok Minimum <span class="text-red-500">*</span></label>
                            <input type="number" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', $produk->stok_minimum) }}" min="0" required
                                   class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors font-mono @error('stok_minimum') border-red-400 @enderror">
                            @error('stok_minimum')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div x-data="{ enabled: {{ old('is_active', $produk->is_active) ? 'true' : 'false' }} }">
                            <input type="hidden" name="is_active" :value="enabled ? '1' : '0'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Status Aktif</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500" x-text="enabled ? 'Produk aktif' : 'Produk nonaktif'"></p>
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
                        <div class="relative w-full aspect-square rounded-xl overflow-hidden border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex items-center justify-center cursor-pointer"
                             @click="$refs.fotoInput.click()">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Preview" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!previewUrl">
                                <div class="text-center px-4">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">Klik untuk ganti foto</p>
                                </div>
                            </template>
                        </div>
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                               x-ref="fotoInput" @change="handleFile($event)" class="hidden">
                        <button type="button" @click="$refs.fotoInput.click()"
                                class="w-full py-2 text-xs font-medium text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            {{ $produk->foto ? 'Ganti Foto' : 'Upload Foto' }}
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
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
