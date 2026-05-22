@extends('layouts.app')

@section('title', $produk->nama_produk)
@section('breadcrumb', 'Detail Produk')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.index', ['tab' => 'produk']) }}"
               class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $produk->nama_produk }}</h1>
                    @if ($produk->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                    @endif
                </div>
                <p class="text-sm font-mono text-slate-500 dark:text-slate-400">{{ $produk->kode_produk }}</p>
            </div>
        </div>
        @role('admin')
        <a href="{{ route('master.produk.edit', $produk) }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
            Edit Produk
        </a>
        @endrole
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ── Main Info Card ── --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Produk</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kategori</dt>
                            <dd class="mt-1 text-sm text-slate-900 dark:text-white">{{ $produk->kategori ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Satuan</dt>
                            <dd class="mt-1 text-sm text-slate-900 dark:text-white">{{ $produk->satuan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Harga Jual</dt>
                            <dd class="mt-1 text-base font-mono font-semibold text-slate-900 dark:text-white">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Harga Pokok (HPP)</dt>
                            <dd class="mt-1 text-base font-mono font-semibold text-slate-900 dark:text-white">Rp {{ number_format($produk->harga_pokok, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stok Saat Ini</dt>
                            <dd class="mt-1 text-base font-mono font-semibold {{ $produk->stok <= $produk->stok_minimum ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                {{ number_format($produk->stok) }} {{ $produk->satuan }}
                                @if ($produk->stok <= $produk->stok_minimum)
                                    <span class="ml-1 text-[11px] font-normal text-red-500 bg-red-50 dark:bg-red-950/40 px-1.5 py-0.5 rounded">Stok rendah</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stok Minimum</dt>
                            <dd class="mt-1 text-base font-mono text-slate-900 dark:text-white">{{ number_format($produk->stok_minimum) }} {{ $produk->satuan }}</dd>
                        </div>
                        @if ($produk->deskripsi)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $produk->deskripsi }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dibuat</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $produk->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diperbarui</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $produk->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Recent Transactions Placeholder --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">5 Transaksi Penjualan Terakhir</h3>
                </div>
                <div class="px-6 py-8 text-center">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Modul Penjualan belum diimplementasi</p>
                </div>
            </div>
        </div>

        {{-- ── Foto & Margin ── --}}
        <div class="space-y-5">
            {{-- Foto --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Foto Produk</h3>
                </div>
                <div class="p-5">
                    @if ($produk->foto)
                        <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama_produk }}" class="w-full aspect-square object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                    @else
                        <div class="w-full aspect-square rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                <p class="text-xs text-slate-400">Belum ada foto</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Margin Info --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Analisis Harga</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                    @php
                        $margin = $produk->harga_jual - $produk->harga_pokok;
                        $marginPct = $produk->harga_pokok > 0 ? ($margin / $produk->harga_pokok * 100) : 0;
                    @endphp
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Harga Jual</span>
                        <span class="font-mono font-medium text-slate-900 dark:text-white">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Harga Pokok</span>
                        <span class="font-mono font-medium text-slate-900 dark:text-white">Rp {{ number_format($produk->harga_pokok, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-3 flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Margin</span>
                        <div class="text-right">
                            <span class="font-mono font-semibold {{ $margin >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                Rp {{ number_format(abs($margin), 0, ',', '.') }}
                            </span>
                            <p class="text-[11px] {{ $margin >= 0 ? 'text-emerald-500' : 'text-red-500' }}">{{ number_format($marginPct, 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
