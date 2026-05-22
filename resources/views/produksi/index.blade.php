@extends('layouts.app')

@section('title', 'Produksi & HPP')
@section('breadcrumb', 'Produksi')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Batch Produksi & Estimasi HPP</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pantau proses pengolahan bahan baku menjadi susu siap jual beserta riwayat HPP.</p>
        </div>
        <div>
            @role('admin')
                <a href="{{ route('produksi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Mulai Batch Baru
                </a>
            @endrole
        </div>
    </div>

    <!-- KPI Analytics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Card 1: Active Batches -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.64 8.38a14.98 14.98 0 00-6.16 12.12A14.98 14.98 0 0015.59 14.37z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 11.75a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Batch Aktif</span>
                <span class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-1">{{ number_format($batchAktif) }}</span>
            </div>
        </div>

        <!-- Card 2: Completed Batches -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Selesai Bulan Ini</span>
                <span class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-1">{{ number_format($batchBulanIni) }}</span>
            </div>
        </div>

        <!-- Card 3: Avg HPP -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-1.958-.659-1.071-.879-1.071-2.303 0-3.182.507-.439 1.233-.659 1.958-.659.768 0 1.536.219 2.058.659m1.958 13.5h-10.5" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Rerata HPP Bulan Ini</span>
                <span class="text-2xl font-bold font-mono text-indigo-600 dark:text-indigo-400 mt-1">Rp {{ number_format($rataHppBulanIni, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Notification Success -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/30 rounded-xl text-sm text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
        <form action="{{ route('produksi.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Search -->
            <div class="flex-1 w-full">
                <label for="search" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Pencarian</label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Nomor prod, batch, nama produk..." 
                        class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white pl-10 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="w-full md:w-44">
                <label for="status" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Status</label>
                <select 
                    id="status" 
                    name="status" 
                    class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Semua Status</option>
                    <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Tanggal Dari -->
            <div class="w-full md:w-40">
                <label for="tanggal_dari" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
                <input 
                    type="date" 
                    id="tanggal_dari" 
                    name="tanggal_dari" 
                    value="{{ request('tanggal_dari') }}" 
                    class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <!-- Tanggal Ke -->
            <div class="w-full md:w-40">
                <label for="tanggal_ke" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Hingga Tanggal</label>
                <input 
                    type="date" 
                    id="tanggal_ke" 
                    name="tanggal_ke" 
                    value="{{ request('tanggal_ke') }}" 
                    class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('produksi.index') }}" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-semibold rounded-lg text-slate-700 dark:text-slate-300 transition-colors focus:outline-none">
                    Reset
                </a>
                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-sm font-semibold rounded-lg text-white shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- History Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No. Produksi</th>
                        <th class="px-6 py-4">Batch / Produk</th>
                        <th class="px-6 py-4">Tanggal Mulai</th>
                        <th class="px-6 py-4">Jumlah Batch</th>
                        <th class="px-6 py-4">Output (Target / Aktual)</th>
                        <th class="px-6 py-4">HPP per Unit</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                    @forelse($produksis as $prod)
                        <tr class="hover:bg-slate-50/55 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-950 dark:text-white">
                                {{ $prod->nomor_produksi }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold block text-slate-900 dark:text-white">{{ $prod->nama_batch }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $prod->produkOutput->nama_produk }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($prod->tanggal_mulai)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-900 dark:text-white">
                                {{ $prod->jumlah_batch }}x
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-slate-900 dark:text-white">
                                    {{ $prod->target_output }} /
                                    @if($prod->actual_output)
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $prod->actual_output }}</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500">Unit</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-900 dark:text-white font-semibold">
                                @if($prod->hpp_per_unit)
                                    Rp {{ number_format($prod->hpp_per_unit, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($prod->status === 'Draft')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        Draft
                                    </span>
                                @elseif($prod->status === 'Proses')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-800 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-900/30">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-500"></span>
                                        </span>
                                        Proses
                                    </span>
                                @elseif($prod->status === 'Selesai')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/30">
                                        Selesai
                                    </span>
                                @elseif($prod->status === 'Dibatalkan')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-400 border border-rose-200/50 dark:border-rose-900/30">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('produksi.show', $prod->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors focus:outline-none">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.64 8.38a14.98 14.98 0 00-6.16 12.12A14.98 14.98 0 0015.59 14.37z" />
                                </svg>
                                Belum ada riwayat transaksi produksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($produksis->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                {{ $produksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
