@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Breadcrumbs & Elegant Header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <nav aria-label="Breadcrumb" class="mb-2">
                <ol class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                    <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Modul Transaksi</a></li>
                    <li>
                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-slate-800 dark:text-slate-200 font-semibold">Penjualan POS</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Riwayat Penjualan POS</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Pemantauan riwayat transaksi dan manajemen retur barang kasir ERP Susu Segar Pak Si.</p>
        </div>
        
        @role('admin')
        <div class="shrink-0 self-start sm:self-auto">
            <a href="{{ route('penjualan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white rounded-xl shadow-md shadow-indigo-500/20 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buka Kasir POS
            </a>
        </div>
        @endrole
    </div>

    <!-- Beautiful Alert Box -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200/50 dark:border-emerald-900/40 rounded-2xl flex items-start gap-3 text-emerald-800 dark:text-emerald-400 shadow-sm" role="alert">
        <div class="p-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 rounded-lg">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.8-11.2a1 1 0 00-1.4-1.4L9 9.6 7.6 8.2a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="text-xs sm:text-sm font-semibold self-center">{{ session('success') }}</div>
    </div>
    @endif

    <!-- Premium Interactive Stats Cards (Hari Ini) -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        
        <!-- Card 1: Jumlah Transaksi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group relative overflow-hidden">
            <div class="absolute top-0 right-0 h-24 w-24 bg-indigo-500/5 rounded-bl-full group-hover:bg-indigo-500/10 transition-colors"></div>
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Transaksi Hari Ini</p>
                    <p class="text-3xl font-extrabold text-slate-900 dark:text-slate-50 font-mono tracking-tight">{{ $jumlahTransaksi }}</p>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl text-indigo-600 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.475A9.09 9.09 0 0 0 12 15.75c-1.905 0-3.67.585-5.126 1.583m10.25 0a9.09 9.09 0 0 1-5.126-1.583m0 0A9.09 9.09 0 0 1 6.874 15.75c-1.457-1-3.22-1.583-5.126-1.583m0 0a9.09 9.09 0 0 1 5.126 1.583M3 9h.008v.008H3V9Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-[10px] text-slate-400 font-medium">Jumlah total cetak nota penjualan hari ini</p>
        </div>

        <!-- Card 2: Omzet -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group relative overflow-hidden">
            <div class="absolute top-0 right-0 h-24 w-24 bg-emerald-500/5 rounded-bl-full group-hover:bg-emerald-500/10 transition-colors"></div>
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Omzet Hari Ini</p>
                    <p class="text-3xl font-extrabold text-slate-900 dark:text-slate-50 font-mono tracking-tight">Rp {{ number_format($omzet, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl text-emerald-600 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-[10px] text-slate-400 font-medium">Akumulasi bruto omzet kasir hari ini</p>
        </div>

        <!-- Card 3: Rata-rata -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group relative overflow-hidden">
            <div class="absolute top-0 right-0 h-24 w-24 bg-blue-500/5 rounded-bl-full group-hover:bg-blue-500/10 transition-colors"></div>
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Rata-rata Transaksi</p>
                    <p class="text-3xl font-extrabold text-slate-900 dark:text-slate-50 font-mono tracking-tight">Rp {{ number_format($rataRata, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-950/40 rounded-xl text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-900/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-[10px] text-slate-400 font-medium">Rata-rata nilai per nota belanja hari ini</p>
        </div>
    </div>

    <!-- Premium Filters Toolbar Panel -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
        <form method="GET" action="{{ route('penjualan.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 items-end">
            <!-- Dari Tanggal -->
            <div class="space-y-1.5">
                <label for="start_date" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="block w-full py-1.5 px-3 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-50 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <!-- Sampai Tanggal -->
            <div class="space-y-1.5">
                <label for="end_date" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="block w-full py-1.5 px-3 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-50 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <!-- Metode Bayar -->
            <div class="space-y-1.5">
                <label for="metode_bayar" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Metode Bayar</label>
                <select id="metode_bayar" name="metode_bayar" class="block w-full py-1.5 px-3 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-50 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="">Semua Metode</option>
                    <option value="Tunai" {{ request('metode_bayar') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="Transfer" {{ request('metode_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="QRIS" {{ request('metode_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <!-- Status -->
            <div class="space-y-1.5">
                <label for="status" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status Transaksi</label>
                <select id="status" name="status" class="block w-full py-1.5 px-3 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-50 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Retur Sebagian" {{ request('status') == 'Retur Sebagian' ? 'selected' : '' }}>Retur Sebagian</option>
                    <option value="Retur Penuh" {{ request('status') == 'Retur Penuh' ? 'selected' : '' }}>Retur Penuh</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl transition-colors focus:outline-none">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.59v3.49a2.25 2.25 0 0 1-.25 1.055l-2.42 4.636a.75.75 0 0 1-1.348-.34V15.75c0-.596-.237-1.168-.659-1.59l-5.432-5.432A2.25 2.25 0 0 1 3 4.818V3.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    Terapkan
                </button>
                <a href="{{ route('penjualan.index') }}" class="inline-flex items-center justify-center p-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-950 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 rounded-xl transition-all border border-slate-200 dark:border-slate-800" title="Reset Filter">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table Card Wrapper -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-850 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-widest select-none">
                        <th class="py-4 px-5">No. Transaksi</th>
                        <th class="py-4 px-4">Tanggal Nota</th>
                        <th class="py-4 px-4">Pelanggan</th>
                        <th class="py-4 px-4 text-right">Grand Total</th>
                        <th class="py-4 px-4">Metode Bayar</th>
                        <th class="py-4 px-4">Kasir / Operator</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse($penjualans as $penjualan)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 text-slate-700 dark:text-slate-300 transition-all">
                        
                        <!-- Transaction number -->
                        <td class="py-3.5 px-5 font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400">
                            <a href="{{ route('penjualan.show', $penjualan->id) }}" class="hover:underline flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                {{ $penjualan->nomor_transaksi }}
                            </a>
                        </td>
                        
                        <!-- Date -->
                        <td class="py-3.5 px-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                            {{ $penjualan->tanggal->format('d/m/Y') }}
                        </td>
                        
                        <!-- Customer -->
                        <td class="py-3.5 px-4 text-xs font-bold text-slate-900 dark:text-slate-100">
                            {{ $penjualan->customer ? $penjualan->customer->nama : 'Pelanggan Umum' }}
                        </td>
                        
                        <!-- Total -->
                        <td class="py-3.5 px-4 text-right font-mono text-xs font-bold text-slate-900 dark:text-slate-50">
                            {{ $penjualan->grand_total_formatted }}
                        </td>
                        
                        <!-- Payment method -->
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center text-[10px] font-bold px-2.5 py-0.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-400">
                                {{ $penjualan->metode_bayar }}
                            </span>
                        </td>
                        
                        <!-- Cashier -->
                        <td class="py-3.5 px-4 text-xs text-slate-500 dark:text-slate-400 font-medium">
                            {{ $penjualan->creator ? $penjualan->creator->name : '-' }}
                        </td>
                        
                        <!-- Status Badge -->
                        <td class="py-3.5 px-4">
                            @if($penjualan->status === 'Lunas')
                            <span class="inline-flex items-center text-[9px] font-bold text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 px-2 py-0.5 rounded-full uppercase tracking-wide">
                                Lunas
                            </span>
                            @elseif($penjualan->status === 'Retur Sebagian')
                            <span class="inline-flex items-center text-[9px] font-bold text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30 px-2 py-0.5 rounded-full uppercase tracking-wide">
                                Retur Sebagian
                            </span>
                            @else
                            <span class="inline-flex items-center text-[9px] font-bold text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-950/20 border border-red-100 dark:border-red-900/30 px-2 py-0.5 rounded-full uppercase tracking-wide">
                                Retur Penuh
                            </span>
                            @endif
                        </td>
                        
                        <!-- Action buttons -->
                        <td class="py-3.5 px-5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Details -->
                                <a href="{{ route('penjualan.show', $penjualan->id) }}" 
                                   class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg hover:border-indigo-500/30 transition-all" 
                                   title="Lihat Detail Nota">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    </svg>
                                </a>
                                
                                <!-- Direct Print -->
                                <a href="{{ route('penjualan.cetak', $penjualan->id) }}" target="_blank" 
                                   class="p-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg hover:border-emerald-500/30 transition-all" 
                                   title="Cetak Struk Thermal">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.618 0-1.113-.487-1.12-1.106L6 18m11.66 0H6.34m9.68-14.333H7.98m7.7 0a48.536 48.536 0 0 1 2.3 2.288c.086.092.107.223.053.336a10.024 10.024 0 0 1-.95 1.583m-10.8 0a10.025 10.025 0 0 1-.95-1.583.344.344 0 0 1 .054-.336 48.574 48.574 0 0 1 2.3-2.288m7.7 0H7.98m0 0L6.72 8.357m1.26-4.5H16.02L17.28 8.357M7.98 3.827H16.02m-.02 4.53H8M17.66 18v-3.07a9.07 9.07 0 0 0-1.424-4.887l-.02-.03a9.07 9.07 0 0 0-6.432-3.83" />
                                    </svg>
                                </a>
                                
                                <!-- Start Return (Admin only) -->
                                @role('admin')
                                    @if($penjualan->status !== 'Retur Penuh')
                                    <a href="{{ route('penjualan.show', $penjualan->id) }}#retur-section" 
                                       class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg hover:border-red-500/30 transition-all" 
                                       title="Proses Retur POS">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                        </svg>
                                    </a>
                                    @endif
                                @endrole
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 max-w-sm mx-auto">
                                <div class="h-12 w-12 rounded-full bg-slate-50 dark:bg-slate-950 flex items-center justify-center text-slate-400 mb-4 border border-slate-200/50 dark:border-slate-800">
                                    <svg class="w-6 h-6 stroke-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.241h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.241h3.86m-18 0h18" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-250">Tidak Ada Transaksi Penjualan</p>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">Saat ini tidak ditemukan riwayat penjualan kasir. Ubah parameter filter tanggal Anda atau catat transaksi baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($penjualans->hasPages())
        <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
            {{ $penjualans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
