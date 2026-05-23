@extends('layouts.app')

@section('title', 'Kartu Stok - ' . ($item->nama_produk ?? $item->nama_bahan))
@section('breadcrumb', 'Persediaan / Kartu Stok')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('persediaan.index') }}" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Kartu Stok Mutasi</h1>
                <p class="text-xs text-slate-500 mt-1">Lacak sejarah keluar masuk dan saldo berjalan persediaan secara kronologis.</p>
            </div>
        </div>
        
        <!-- EXPORT BUTTON -->
        <div class="shrink-0">
            <a href="{{ route('persediaan.kartu_stok.export', ['type' => $type, 'id' => $item->id, 'dari' => request('dari'), 'sampai' => request('sampai')]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg shadow-sm transition-all focus:outline-none">
                <svg class="w-4 h-4 text-slate-550" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Ekspor Excel (.xlsx)
            </a>
        </div>
    </div>

    <!-- Metadata Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider block">Kode Item</span>
            <span class="text-sm font-mono font-bold text-slate-900 dark:text-white block mt-1">{{ $item->kode_produk ?? $item->kode_bahan }}</span>
        </div>
        <div>
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider block">Nama Item</span>
            <span class="text-sm font-semibold text-slate-900 dark:text-white block mt-1">{{ $item->nama_produk ?? $item->nama_bahan }}</span>
        </div>
        <div>
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider block">Satuan Takaran</span>
            <span class="text-sm font-medium text-slate-900 dark:text-white block mt-1">{{ $item->satuan }}</span>
        </div>
        <div>
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider block">Stok Aktual Saat Ini</span>
            <span class="text-sm font-mono font-extrabold text-indigo-600 dark:text-indigo-400 block mt-1">{{ number_format($item->stok) }}</span>
        </div>
    </div>

    <!-- DATE RANGE FILTER BAR -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-sm">
        <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="grid grid-cols-2 gap-4 flex-1">
                <div class="space-y-1">
                    <label for="dari" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" id="dari" name="dari" value="{{ request('dari') }}"
                           class="block w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors">
                </div>
                <div class="space-y-1">
                    <label for="sampai" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}"
                           class="block w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors">
                </div>
            </div>
            <div class="flex gap-2.5 shrink-0">
                <a href="{{ route('persediaan.kartu_stok', ['type' => $type, 'id' => $item->id]) }}"
                   class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors border border-transparent">
                    Reset Filter
                </a>
                <button type="submit"
                        class="px-4 py-2 text-xs font-semibold bg-indigo-650 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all focus:outline-none">
                    Filter Mutasi
                </button>
            </div>
        </form>
    </div>

    <!-- MUTASI CARD TABLE -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Daftar Mutasi Stok</h3>
        </div>

        @if ($filteredMutations->isEmpty())
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <p class="text-xs font-medium text-slate-500">Tidak ada riwayat mutasi dalam filter tanggal ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4 text-center">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Referensi</th>
                            <th class="px-6 py-4 text-right">Masuk (+)</th>
                            <th class="px-6 py-4 text-right">Keluar (-)</th>
                            <th class="px-6 py-4 text-right bg-slate-50 dark:bg-slate-900/30">Saldo Berjalan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        @foreach ($filteredMutations as $mut)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors">
                                <td class="px-6 py-4 text-center text-xs text-slate-500">
                                    {{ Carbon\Carbon::parse($mut['tanggal'])->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                    {{ $mut['keterangan'] }}
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-bold text-slate-500">
                                    {{ $mut['referensi'] }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-450">
                                    {{ $mut['masuk'] > 0 ? '+' . number_format($mut['masuk']) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-red-600 dark:text-red-450">
                                    {{ $mut['keluar'] > 0 ? '-' . number_format($mut['keluar']) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-extrabold text-slate-900 dark:text-white bg-slate-50/30 dark:bg-slate-900/10">
                                    {{ number_format($mut['saldo']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
