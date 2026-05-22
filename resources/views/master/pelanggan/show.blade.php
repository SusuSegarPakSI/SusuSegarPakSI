@extends('layouts.app')
@section('title', $pelanggan->nama)
@section('breadcrumb', 'Detail Pelanggan')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.index', ['tab' => 'pelanggan']) }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $pelanggan->nama }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $pelanggan->tipe === 'Mitra' ? 'text-indigo-700 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-950/40' : 'text-slate-700 bg-slate-100 dark:text-slate-400 dark:bg-slate-800' }}">{{ $pelanggan->tipe }}</span>
                    @if ($pelanggan->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                    @endif
                </div>
                <p class="text-sm font-mono text-slate-500 dark:text-slate-400">{{ $pelanggan->kode_pelanggan }}</p>
            </div>
        </div>
        @role('admin')
        <a href="{{ route('master.pelanggan.edit', $pelanggan) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
            Edit Pelanggan
        </a>
        @endrole
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Kontak</h3></div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Telepon</dt>
                            <dd class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ $pelanggan->telepon ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email</dt>
                            <dd class="mt-1 text-sm text-slate-900 dark:text-white">{{ $pelanggan->email ?: '—' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Alamat</dt>
                            <dd class="mt-1 text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $pelanggan->alamat ?: '—' }}</dd>
                        </div>
                        @if ($pelanggan->catatan)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Catatan Internal</dt>
                                <dd class="mt-1 text-sm text-slate-700 dark:text-slate-300">{{ $pelanggan->catatan }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dibuat</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $pelanggan->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diperbarui</dt>
                            <dd class="mt-1 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $pelanggan->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Riwayat Penjualan Placeholder --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Riwayat Penjualan</h3></div>
                <div class="px-6 py-8 text-center">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Modul Penjualan belum diimplementasi</p>
                </div>
            </div>
        </div>

        {{-- Sidebar: Saldo Piutang --}}
        <div class="space-y-5">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800"><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Ringkasan Keuangan</h3></div>
                <div class="px-5 py-4 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Saldo Piutang</p>
                        <p class="text-2xl font-mono font-bold {{ $pelanggan->saldo_piutang > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400 dark:text-slate-500' }}">
                            Rp {{ number_format($pelanggan->saldo_piutang, 0, ',', '.') }}
                        </p>
                        @if ($pelanggan->saldo_piutang > 0)
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">Ada tagihan belum terlunasi</p>
                        @else
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">Tidak ada tagihan</p>
                        @endif
                    </div>
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Saldo Awal</p>
                        <p class="text-lg font-mono font-semibold text-slate-700 dark:text-slate-300">Rp {{ number_format($pelanggan->saldo_awal_piutang, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
