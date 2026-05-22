@extends('layouts.app')
@section('title', $bahanBaku->nama_bahan)
@section('breadcrumb', 'Detail Bahan Baku')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.index', ['tab' => 'bahan']) }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $bahanBaku->nama_bahan }}</h1>
                    @if ($bahanBaku->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                    @endif
                </div>
                <p class="text-sm font-mono text-slate-500 dark:text-slate-400">{{ $bahanBaku->kode_bahan }}</p>
            </div>
        </div>
        @role('admin')
        <a href="{{ route('master.bahan-baku.edit', $bahanBaku) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
            Edit Bahan
        </a>
        @endrole
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Bahan Baku</h3></div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kategori</dt>
                            @php $kategoriColor = match($bahanBaku->kategori) { 'Baku' => 'text-blue-700 bg-blue-50 dark:text-blue-400 dark:bg-blue-950/40', 'Penolong' => 'text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/40', 'Kemasan' => 'text-violet-700 bg-violet-50 dark:text-violet-400 dark:bg-violet-950/40', default => 'text-slate-700 bg-slate-100' }; @endphp
                            <dd class="mt-1"><span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $kategoriColor }}">{{ $bahanBaku->kategori }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Satuan</dt>
                            <dd class="mt-1 text-sm text-slate-900 dark:text-white">{{ $bahanBaku->satuan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Harga Rata-rata (Moving Average)</dt>
                            <dd class="mt-1 text-base font-mono font-semibold text-slate-900 dark:text-white">Rp {{ number_format($bahanBaku->harga_rata_rata, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stok Saat Ini</dt>
                            <dd class="mt-1 text-base font-mono font-semibold {{ $bahanBaku->stok <= $bahanBaku->stok_minimum ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                {{ number_format($bahanBaku->stok) }} {{ $bahanBaku->satuan }}
                                @if ($bahanBaku->stok <= $bahanBaku->stok_minimum)<span class="ml-1 text-[11px] font-normal text-red-500 bg-red-50 dark:bg-red-950/40 px-1.5 py-0.5 rounded">Stok rendah</span>@endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stok Minimum</dt>
                            <dd class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ number_format($bahanBaku->stok_minimum) }} {{ $bahanBaku->satuan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Supplier Utama</dt>
                            <dd class="mt-1 text-sm text-slate-900 dark:text-white">
                                @if ($bahanBaku->supplierUtama)
                                    <a href="{{ route('master.supplier.show', $bahanBaku->supplierUtama) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $bahanBaku->supplierUtama->nama_supplier }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dibuat</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $bahanBaku->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diperbarui</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $bahanBaku->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">5 Pembelian Terakhir</h3></div>
                <div class="px-6 py-8 text-center">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m2.25 0h1.5a1.125 1.125 0 0 0 1.125-1.125V11.25m-19.5 0h19.5L16.666 4.675A2.25 2.25 0 0 0 14.772 3.75H7.228a2.25 2.25 0 0 0-1.894.925L2.25 11.25Z" /></svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Modul Pembelian belum diimplementasi</p>
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-5">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Status Stok</h3></div>
                <div class="px-5 py-4 space-y-3">
                    @php
                        $pct = $bahanBaku->stok_minimum > 0 ? min(100, ($bahanBaku->stok / $bahanBaku->stok_minimum) * 100) : 100;
                        $barColor = $bahanBaku->stok <= $bahanBaku->stok_minimum ? 'bg-red-500' : ($pct < 150 ? 'bg-amber-500' : 'bg-emerald-500');
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1.5">
                            <span class="text-slate-500 dark:text-slate-400">Stok vs Minimum</span>
                            <span class="font-mono font-medium {{ $bahanBaku->stok <= $bahanBaku->stok_minimum ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ number_format($bahanBaku->stok) }} / {{ number_format($bahanBaku->stok_minimum) }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-2 rounded-full {{ $barColor }} transition-all" style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
