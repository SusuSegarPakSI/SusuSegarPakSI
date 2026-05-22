@extends('layouts.app')
@section('title', $supplier->nama_supplier)
@section('breadcrumb', 'Detail Supplier')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.index', ['tab' => 'supplier']) }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $supplier->nama_supplier }}</h1>
                    @if ($supplier->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                    @endif
                </div>
                <p class="text-sm font-mono text-slate-500 dark:text-slate-400">{{ $supplier->kode_supplier }}</p>
            </div>
        </div>
        @role('admin')
        <a href="{{ route('master.supplier.edit', $supplier) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
            Edit Supplier
        </a>
        @endrole
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-5">
            {{-- Informasi Kontak --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Kontak</h3></div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Contact Person</dt>
                            <dd class="mt-1 text-sm text-slate-900 dark:text-white">{{ $supplier->nama_kontak ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Telepon</dt>
                            <dd class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ $supplier->telepon ?: '—' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Alamat</dt>
                            <dd class="mt-1 text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $supplier->alamat ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dibuat</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500">{{ $supplier->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diperbarui</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500">{{ $supplier->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Bahan Baku terkait --}}
            @if ($supplier->bahanBakus && $supplier->bahanBakus->count() > 0)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Bahan Baku dari Supplier Ini</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($supplier->bahanBakus as $bahan)
                        <div class="flex items-center justify-between px-6 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $bahan->nama_bahan }}</p>
                                <p class="text-xs font-mono text-slate-400">{{ $bahan->kode_bahan }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-mono text-slate-600 dark:text-slate-300">Rp {{ number_format($bahan->harga_rata_rata, 0, ',', '.') }}</p>
                                <p class="text-[11px] text-slate-400">Stok: {{ number_format($bahan->stok) }} {{ $bahan->satuan }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Riwayat Pembelian Placeholder --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Riwayat Pembelian</h3></div>
                <div class="px-6 py-8 text-center">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m2.25 0h1.5a1.125 1.125 0 0 0 1.125-1.125V11.25m-19.5 0h19.5L16.666 4.675A2.25 2.25 0 0 0 14.772 3.75H7.228a2.25 2.25 0 0 0-1.894.925L2.25 11.25Z" /></svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Modul Pembelian belum diimplementasi</p>
                </div>
            </div>
        </div>

        {{-- Sidebar: Saldo Hutang --}}
        <div class="space-y-5">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Ringkasan Keuangan</h3></div>
                <div class="px-5 py-4 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Saldo Hutang</p>
                        <p class="text-2xl font-mono font-bold {{ $supplier->saldo_hutang > 0 ? 'text-red-600 dark:text-red-400' : 'text-slate-400 dark:text-slate-500' }}">
                            Rp {{ number_format($supplier->saldo_hutang, 0, ',', '.') }}
                        </p>
                        @if ($supplier->saldo_hutang > 0)
                            <p class="text-xs text-red-600 dark:text-red-400 mt-0.5">Ada hutang belum terlunasi</p>
                        @else
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">Tidak ada hutang</p>
                        @endif
                    </div>
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Saldo Awal</p>
                        <p class="text-lg font-mono font-semibold text-slate-700 dark:text-slate-300">Rp {{ number_format($supplier->saldo_awal_hutang, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
