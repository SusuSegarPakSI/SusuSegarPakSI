@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('breadcrumb', 'Laporan Keuangan')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ request('tab', 'laba_rugi') }}' }">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Laporan Keuangan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Analisis performa bisnis, pantau arus kas, dan tinjau posisi neraca keuangan Susu Segar Pak Si.</p>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
        <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col gap-4 md:flex-row md:items-end">
            <!-- Hidden tab state to preserve active tab on filter submit -->
            <input type="hidden" name="tab" :value="activeTab">
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 flex-grow">
                <div>
                    <label for="dari" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
                    <input type="date" name="dari" id="dari" value="{{ $dari->toDateString() }}" 
                           class="w-full text-sm rounded-lg border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all">
                </div>
                <div>
                    <label for="sampai" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                    <input type="date" name="sampai" id="sampai" value="{{ $sampai->toDateString() }}" 
                           class="w-full text-sm rounded-lg border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all">
                </div>
            </div>

            <div class="flex gap-2 shrink-0">
                <button type="submit" 
                        class="inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('laporan.index', ['dari' => now()->startOfMonth()->toDateString(), 'sampai' => now()->toDateString()]) }}" 
                   class="inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-medium border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm w-full sm:w-auto">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Laba Bersih -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Laba Bersih</p>
                    <p class="mt-2 text-2xl font-bold font-mono @if($labaRugi['laba_bersih'] >= 0) text-emerald-600 dark:text-emerald-400 @else text-rose-600 dark:text-rose-400 @endif">
                        {{ formatRupiah($labaRugi['laba_bersih']) }}
                    </p>
                </div>
                <div class="p-2.5 @if($labaRugi['laba_bersih'] >= 0) bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 @else bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 @endif rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306 8.9-8.91M21 11.5v-5h-5" />
                    </svg>
                </div>
            </div>
            <div class="mt-2.5 flex items-center gap-1">
                <span class="text-xs text-slate-400">Periode terpilih</span>
            </div>
        </div>

        <!-- Arus Kas Akhir -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Saldo Kas Akhir</p>
                    <p class="mt-2 text-2xl font-bold text-indigo-600 dark:text-indigo-400 font-mono">
                        {{ formatRupiah($arusKas['saldo_akhir']) }}
                    </p>
                </div>
                <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/40 rounded-lg text-indigo-600 dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                    </svg>
                </div>
            </div>
            <div class="mt-2.5">
                <span class="text-xs text-slate-400">Akumulasi Kas s/d {{ $sampai->translatedFormat('d M Y') }}</span>
            </div>
        </div>

        <!-- Total Aset -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Aset</p>
                    <p class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400 font-mono">
                        {{ formatRupiah($neraca['aset']['total_aset']) }}
                    </p>
                </div>
                <div class="p-2.5 bg-blue-50 dark:bg-blue-950/40 rounded-lg text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2.5">
                <span class="text-xs text-slate-400">Posisi Neraca s/d {{ $sampai->translatedFormat('d M Y') }}</span>
            </div>
        </div>

        <!-- Total Hutang Usaha -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hutang Usaha</p>
                    <p class="mt-2 text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono">
                        {{ formatRupiah($neraca['kewajiban']['hutang_usaha']) }}
                    </p>
                </div>
                <div class="p-2.5 bg-amber-50 dark:bg-amber-950/40 rounded-lg text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2.5">
                <span class="text-xs text-slate-400">Total kewajiban yang aktif</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="flex space-x-6" aria-label="Tabs">
            <button @click="activeTab = 'laba_rugi'"
                    :class="activeTab === 'laba_rugi' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Laba Rugi
            </button>
            <button @click="activeTab = 'arus_kas'"
                    :class="activeTab === 'arus_kas' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Arus Kas
            </button>
            <button @click="activeTab = 'neraca'"
                    :class="activeTab === 'neraca' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Neraca
            </button>
            <button @click="activeTab = 'beban'"
                    :class="activeTab === 'beban' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Beban Operasional
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div>
        <!-- LABA RUGI TAB -->
        <div x-show="activeTab === 'laba_rugi'" class="space-y-6" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Laporan Laba Rugi</h3>
                <a href="{{ route('laporan.laba-rugi.export', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg transition-colors border border-emerald-200/50 dark:border-emerald-900/50 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Excel
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Table -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm lg:col-span-7">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4 text-right">Nilai (IDR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-150 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                            <!-- Revenue -->
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-4 font-medium">Omzet Penjualan</td>
                                <td class="px-6 py-4 text-right font-mono font-medium">{{ formatRupiah($labaRugi['omzet']) }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 text-rose-600 dark:text-rose-400">
                                <td class="px-6 py-4 font-medium pl-8">(-) Retur Penjualan</td>
                                <td class="px-6 py-4 text-right font-mono font-medium">-{{ formatRupiah($labaRugi['total_retur']) }}</td>
                            </tr>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/20 font-bold border-t border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white">
                                <td class="px-6 py-4">Penjualan Bersih</td>
                                <td class="px-6 py-4 text-right font-mono">{{ formatRupiah($labaRugi['penjualan_bersih']) }}</td>
                            </tr>

                            <!-- HPP -->
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 text-rose-600 dark:text-rose-400">
                                <td class="px-6 py-4 font-medium">(-) HPP Terjual</td>
                                <td class="px-6 py-4 text-right font-mono font-medium">-{{ formatRupiah($labaRugi['hpp_terjual']) }}</td>
                            </tr>
                            <tr class="bg-indigo-50/30 dark:bg-indigo-950/10 font-bold border-y border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white">
                                <td class="px-6 py-4">LABA KOTOR</td>
                                <td class="px-6 py-4 text-right font-mono text-indigo-600 dark:text-indigo-400">{{ formatRupiah($labaRugi['laba_kotor']) }}</td>
                            </tr>

                            <!-- Operating Expenses -->
                            <tr class="bg-slate-50/40 dark:bg-slate-950/5 font-semibold text-slate-800 dark:text-slate-200">
                                <td class="px-6 py-3" colspan="2">Beban Operasional:</td>
                            </tr>
                            @if(count($labaRugi['breakdown_beban']) > 0)
                                @foreach($labaRugi['breakdown_beban'] as $beban)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 text-slate-600 dark:text-slate-400">
                                    <td class="px-6 py-3.5 pl-8">{{ $beban['nama'] }}</td>
                                    <td class="px-6 py-3.5 text-right font-mono">-{{ formatRupiah($beban['nominal']) }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-6 py-4 text-center text-slate-400 dark:text-slate-500 text-xs italic" colspan="2">
                                        Tidak ada beban operasional tercatat pada periode ini
                                    </td>
                                </tr>
                            @endif

                            <tr class="bg-slate-50/70 dark:bg-slate-950/20 font-bold border-t border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white">
                                <td class="px-6 py-4">Total Beban Operasional</td>
                                <td class="px-6 py-4 text-right font-mono text-rose-600 dark:text-rose-400">-{{ formatRupiah($labaRugi['total_beban_operasional']) }}</td>
                            </tr>

                            <!-- Net Profit -->
                            <tr class="bg-indigo-600 dark:bg-indigo-900 font-bold border-t-2 border-indigo-700 text-white">
                                <td class="px-6 py-5 text-base">LABA BERSIH OPERASIONAL</td>
                                <td class="px-6 py-5 text-right font-mono text-lg tracking-wider">{{ formatRupiah($labaRugi['laba_bersih']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Charts / Insights -->
                <div class="space-y-6 lg:col-span-5">
                    <!-- Chart 1: Beban Breakdown -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Breakdown Beban Operasional</h4>
                        @if(count($labaRugi['breakdown_beban']) > 0)
                            <div class="relative flex items-center justify-center min-h-[220px]">
                                <canvas id="expenseChart" class="max-w-[280px] max-h-[280px]"></canvas>
                            </div>
                        @else
                            <div class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <svg class="w-10 h-10 mx-auto opacity-30 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                                </svg>
                                <p class="text-xs">Data diagram beban belum tersedia.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Chart 2: Monthly Profit Trend (shown only if range > 1 month) -->
                    @if(count($labaRugi['breakdown_bulanan']) > 0)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Tren Bulanan (Omzet vs Laba Bersih)</h4>
                        <div class="relative h-64">
                            <canvas id="monthlyTrendChart"></canvas>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ARUS KAS TAB -->
        <div x-show="activeTab === 'arus_kas'" class="space-y-6" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Laporan Arus Kas</h3>
                <a href="{{ route('laporan.arus-kas.export', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg transition-colors border border-emerald-200/50 dark:border-emerald-900/50 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Excel
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Table -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm lg:col-span-8">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                                <th class="px-6 py-4">Aktivitas Kas</th>
                                <th class="px-6 py-4 text-right">Nominal (IDR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-150 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                            <!-- Saldo Awal -->
                            <tr class="bg-slate-50/50 dark:bg-slate-950/10 font-semibold text-slate-900 dark:text-white">
                                <td class="px-6 py-4">SALDO AWAL KAS</td>
                                <td class="px-6 py-4 text-right font-mono">{{ formatRupiah($arusKas['saldo_awal']) }}</td>
                            </tr>

                            <!-- Penerimaan -->
                            <tr class="bg-slate-50/20 dark:bg-slate-950/5 font-semibold">
                                <td class="px-6 py-2" colspan="2">Penerimaan Kas (Kas Masuk):</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Penerimaan dari Penjualan POS</td>
                                <td class="px-6 py-3.5 text-right font-mono text-emerald-600 dark:text-emerald-400">+{{ formatRupiah($arusKas['kas_masuk_penjualan']) }}</td>
                            </tr>
                            <tr class="bg-slate-50/40 dark:bg-slate-950/15 font-semibold text-slate-800 dark:text-slate-200">
                                <td class="px-6 py-3.5 pl-4">Total Penerimaan Kas</td>
                                <td class="px-6 py-3.5 text-right font-mono text-emerald-600 dark:text-emerald-400">+{{ formatRupiah($arusKas['total_kas_masuk']) }}</td>
                            </tr>

                            <!-- Pengeluaran -->
                            <tr class="bg-slate-50/20 dark:bg-slate-950/5 font-semibold">
                                <td class="px-6 py-2" colspan="2">Pengeluaran Kas (Kas Keluar):</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Pembelian Bahan Baku (Lunas/Tunai)</td>
                                <td class="px-6 py-3.5 text-right font-mono text-rose-600 dark:text-rose-400">-{{ formatRupiah($arusKas['kas_keluar_pembelian_lunas']) }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Pembayaran Hutang Supplier</td>
                                <td class="px-6 py-3.5 text-right font-mono text-rose-600 dark:text-rose-400">-{{ formatRupiah($arusKas['kas_keluar_pembayaran_hutang']) }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Biaya Pengeluaran Produksi (Overhead & Tenaga Kerja)</td>
                                <td class="px-6 py-3.5 text-right font-mono text-rose-600 dark:text-rose-400">-{{ formatRupiah($arusKas['kas_keluar_biaya_produksi']) }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Beban Lain-lain</td>
                                <td class="px-6 py-3.5 text-right font-mono text-rose-600 dark:text-rose-400">-{{ formatRupiah($arusKas['kas_keluar_beban_lain']) }}</td>
                            </tr>
                            <tr class="bg-slate-50/40 dark:bg-slate-950/15 font-semibold text-slate-800 dark:text-slate-200">
                                <td class="px-6 py-3.5 pl-4">Total Pengeluaran Kas</td>
                                <td class="px-6 py-3.5 text-right font-mono text-rose-600 dark:text-rose-400">-{{ formatRupiah($arusKas['total_kas_keluar']) }}</td>
                            </tr>

                            <!-- Net Cash Flow -->
                            <tr class="bg-slate-50/70 dark:bg-slate-950/20 font-bold text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800">
                                <td class="px-6 py-4">ARUS KAS BERSIH (NET CASH FLOW)</td>
                                <td class="px-6 py-4 text-right font-mono @if($arusKas['net_cash_flow'] >= 0) text-emerald-600 dark:text-emerald-400 @else text-rose-600 dark:text-rose-400 @endif">
                                    {{ $arusKas['net_cash_flow'] >= 0 ? '+' : '' }}{{ formatRupiah($arusKas['net_cash_flow']) }}
                                </td>
                            </tr>

                            <!-- Saldo Akhir -->
                            <tr class="bg-indigo-600 dark:bg-indigo-900 font-bold border-t-2 border-indigo-700 text-white">
                                <td class="px-6 py-5 text-base">SALDO AKHIR KAS</td>
                                <td class="px-6 py-5 text-right font-mono text-lg tracking-wider">{{ formatRupiah($arusKas['saldo_akhir']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Config Saldo Awal -->
                <div class="space-y-6 lg:col-span-4">
                    @role('admin')
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider mb-2">Atur Saldo Awal</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Tentukan nominal saldo kas awal perusahaan untuk memulai perhitungan arus kas periode ini.</p>
                        
                        <form action="{{ route('laporan.saldo.set') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="dari" value="{{ $dari->toDateString() }}">
                            <input type="hidden" name="sampai" value="{{ $sampai->toDateString() }}">
                            
                            <div>
                                <label for="saldo_kas_awal" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Saldo Awal (Rp)</label>
                                <div class="relative mt-1 rounded-lg shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-slate-500 text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="saldo_kas_awal" id="saldo_kas_awal" step="any" min="0" value="{{ $saldoKasAwal }}" required
                                           class="w-full text-sm rounded-lg border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 pl-10 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all font-mono">
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                                Simpan Saldo Awal
                            </button>
                        </form>
                    </div>
                    @endrole
                </div>
            </div>
        </div>

        <!-- NERACA TAB -->
        <div x-show="activeTab === 'neraca'" class="space-y-6" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Laporan Neraca (Posisi Keuangan)</h3>
                <a href="{{ route('laporan.neraca.export', ['sampai' => $sampai->toDateString()]) }}" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg transition-colors border border-emerald-200/50 dark:border-emerald-900/50 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Excel
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Table -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm lg:col-span-8">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                                <th class="px-6 py-4">Komponen Neraca</th>
                                <th class="px-6 py-4 text-right">Nilai (IDR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-150 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                            <!-- ASET -->
                            <tr class="bg-slate-50/35 dark:bg-slate-950/5 font-semibold text-slate-900 dark:text-white">
                                <td class="px-6 py-2.5" colspan="2">ASET (AKTIVA)</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Kas & Setara Kas</td>
                                <td class="px-6 py-3.5 text-right font-mono">{{ formatRupiah($neraca['aset']['kas']) }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Piutang Dagang / Pelanggan</td>
                                <td class="px-6 py-3.5 text-right font-mono">{{ formatRupiah($neraca['aset']['piutang']) }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Persediaan Produk Jadi</td>
                                <td class="px-6 py-3.5 text-right font-mono">{{ formatRupiah($neraca['aset']['persediaan_produk']) }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Persediaan Bahan Baku</td>
                                <td class="px-6 py-3.5 text-right font-mono">{{ formatRupiah($neraca['aset']['persediaan_bahan']) }}</td>
                            </tr>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/20 font-bold border-t border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white">
                                <td class="px-6 py-4 text-indigo-600 dark:text-indigo-400">TOTAL ASET (A)</td>
                                <td class="px-6 py-4 text-right font-mono text-indigo-600 dark:text-indigo-400">{{ formatRupiah($neraca['aset']['total_aset']) }}</td>
                            </tr>

                            <!-- KEWAJIBAN -->
                            <tr class="bg-slate-50/35 dark:bg-slate-950/5 font-semibold text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800">
                                <td class="px-6 py-2.5" colspan="2">KEWAJIBAN (PASIVA)</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Hutang Dagang / Supplier</td>
                                <td class="px-6 py-3.5 text-right font-mono">{{ formatRupiah($neraca['kewajiban']['hutang_usaha']) }}</td>
                            </tr>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/20 font-bold border-t border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white">
                                <td class="px-6 py-4">TOTAL KEWAJIBAN (B)</td>
                                <td class="px-6 py-4 text-right font-mono text-rose-600 dark:text-rose-400">{{ formatRupiah($neraca['kewajiban']['total_kewajiban']) }}</td>
                            </tr>

                            <!-- EKUITAS -->
                            <tr class="bg-slate-50/35 dark:bg-slate-950/5 font-semibold text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800">
                                <td class="px-6 py-2.5" colspan="2">EKUITAS</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-6 py-3.5 pl-8 text-slate-600 dark:text-slate-400">Modal Modal Pemilik / Laba Ditahan</td>
                                <td class="px-6 py-3.5 text-right font-mono">{{ formatRupiah($neraca['ekuitas']['modal_ekuitas']) }}</td>
                            </tr>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/20 font-bold border-t border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white">
                                <td class="px-6 py-4">TOTAL EKUITAS (C)</td>
                                <td class="px-6 py-4 text-right font-mono">{{ formatRupiah($neraca['ekuitas']['modal_ekuitas']) }}</td>
                            </tr>

                            <!-- PASIVA TOTAL -->
                            <tr class="bg-indigo-600 dark:bg-indigo-900 font-bold border-t-2 border-indigo-700 text-white">
                                <td class="px-6 py-5 text-base">TOTAL PASIVA (KEWAJIBAN + EKUITAS) (B+C)</td>
                                <td class="px-6 py-5 text-right font-mono text-lg tracking-wider">{{ formatRupiah($neraca['total_kewajiban_ekuitas']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Balanced Check card -->
                <div class="space-y-6 lg:col-span-4">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center">
                        @if($neraca['balanced'])
                            <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/30 mb-4 animate-pulse">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Neraca Seimbang</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 max-w-[240px]">Laporan keuangan Anda seimbang. Nilai Total Aset sama dengan Total Kewajiban ditambah Ekuitas perusahaan.</p>
                        @else
                            <div class="w-16 h-16 rounded-full bg-rose-50 dark:bg-rose-950/30 flex items-center justify-center text-rose-600 dark:text-rose-400 border border-rose-200/50 dark:border-rose-900/30 mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-rose-600 dark:text-rose-400">Neraca Tidak Seimbang</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 max-w-[240px]">Terdapat selisih antara nilai Aktiva dan Pasiva. Tinjau kembali pencatatan saldo awal, inventaris, atau hutang Anda.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- BEBAN OPERASIONAL TAB -->
        <div x-show="activeTab === 'beban'" class="space-y-6" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Pengelolaan Beban Operasional Lain-Lain</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Add Form (Admin Only) -->
                @role('admin')
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm lg:col-span-4 self-start">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Tambah Beban Lain</h4>
                    
                    <form action="{{ route('laporan.beban.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="dari" value="{{ $dari->toDateString() }}">
                        <input type="hidden" name="sampai" value="{{ $sampai->toDateString() }}">

                        <div>
                            <label for="tanggal" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ now()->toDateString() }}" required
                                   class="w-full text-sm rounded-lg border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all">
                        </div>

                        <div>
                            <label for="keterangan" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Keterangan Beban</label>
                            <input type="text" name="keterangan" id="keterangan" placeholder="Contoh: Tagihan Listrik Mei, Sewa Toko" required
                                   class="w-full text-sm rounded-lg border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all">
                        </div>

                        <div>
                            <label for="nominal" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Nominal Beban (Rp)</label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 text-sm">Rp</span>
                                </div>
                                <input type="number" name="nominal" id="nominal" step="any" min="1" placeholder="0" required
                                       class="w-full text-sm rounded-lg border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 pl-10 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all font-mono">
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                            Simpan Beban
                        </button>
                    </form>
                </div>
                @endrole

                <!-- List Table -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm lg:col-span-8">
                    @if($bebanLainList->isEmpty())
                        <div class="py-16 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.559c.74.47 1.837.227 2.275-.51l.001-.003c.437-.743.205-1.72-.549-2.127l-.082-.045c-.744-.407-.978-1.383-.54-2.128.438-.737 1.536-.98 2.275-.508l.88.56M12 3v3m0 12v3" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Tidak ada pengeluaran beban operasional.</p>
                            <p class="text-xs text-slate-500 mt-0.5">Semua data beban operasional periode berjalan akan tampil di sini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Keterangan</th>
                                        <th class="px-6 py-4 text-right">Nominal</th>
                                        <th class="px-6 py-4 text-center">Dicatat Oleh</th>
                                        @role('admin')
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                                    @foreach($bebanLainList as $beban)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $beban->tanggal->translatedFormat('d M Y') }}</td>
                                            <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $beban->keterangan }}</td>
                                            <td class="px-6 py-4 text-right font-mono font-semibold text-slate-900 dark:text-white">{{ formatRupiah($beban->nominal) }}</td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                                                {{ $beban->creator->name ?? 'System' }}
                                            </td>
                                            @role('admin')
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                <form action="{{ route('laporan.beban.destroy', $beban->id) }}" method="POST" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan beban ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 transition-colors">
                                                        <svg class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                            @endrole
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark') || 
                       localStorage.getItem('theme') === 'dark';

        const textMutedColor = isDark ? '#94a3b8' : '#64748b';
        const borderColor = isDark ? '#334155' : '#e2e8f0';

        // 1. Expense Pie Chart
        const expenseCanvas = document.getElementById('expenseChart');
        if (expenseCanvas) {
            const breakdownData = @json($labaRugi['breakdown_beban']);
            
            const labels = breakdownData.map(item => item.nama);
            const data = breakdownData.map(item => item.nominal);
            
            const colors = [
                '#6366f1', // Indigo
                '#f43f5e', // Rose
                '#0ea5e9', // Sky
                '#10b981', // Emerald
                '#f59e0b', // Amber
                '#8b5cf6', // Violet
                '#ec4899', // Pink
                '#14b8a6'  // Teal
            ];

            new Chart(expenseCanvas, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: isDark ? 2 : 1,
                        borderColor: isDark ? '#0f172a' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textMutedColor,
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Monthly Trend Chart
        const trendCanvas = document.getElementById('monthlyTrendChart');
        if (trendCanvas) {
            const monthlyData = @json($labaRugi['breakdown_bulanan']);
            
            const labels = monthlyData.map(item => item.bulan);
            const omzet = monthlyData.map(item => item.omzet);
            const labaBersih = monthlyData.map(item => item.laba_bersih);

            new Chart(trendCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Omzet',
                            data: omzet,
                            backgroundColor: '#6366f1',
                            borderColor: '#4f46e5',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Laba Bersih',
                            data: labaBersih,
                            type: 'line',
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            tension: 0.1,
                            pointStyle: 'circle',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textMutedColor
                            }
                        },
                        y: {
                            grid: {
                                color: borderColor
                            },
                            ticks: {
                                color: textMutedColor,
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact", compactDisplay: "short" }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: textMutedColor
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
