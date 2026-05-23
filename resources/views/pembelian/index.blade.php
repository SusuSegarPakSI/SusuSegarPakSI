@extends('layouts.app')

@section('title', 'Pembelian Bahan Baku')
@section('breadcrumb', 'Pembelian')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Pembelian Bahan Baku</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola transaksi pembelian bahan baku, penerimaan stok, dan pencatatan hutang supplier.</p>
        </div>
        @role('admin')
        <div class="shrink-0">
            <a href="{{ route('pembelian.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Nota Pembelian
            </a>
        </div>
        @endrole
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <!-- Card 1: Total Pembelian Bulan Ini -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pembelian Bulan Ini</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white font-mono">{{ formatRupiah($totalBulanIni) }}</p>
                </div>
                <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/40 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2.5">
                <span class="text-xs text-slate-400">Periode berjalan: {{ Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>
        </div>

        <!-- Card 2: Total Hutang Aktif -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Hutang Supplier</p>
                    <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400 font-mono">{{ formatRupiah($totalHutangAktif) }}</p>
                </div>
                <div class="p-2.5 bg-red-50 dark:bg-red-950/40 rounded-lg">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.559c.74.47 1.837.227 2.275-.51l.001-.003c.437-.743.205-1.72-.549-2.127l-.082-.045c-.744-.407-.978-1.383-.54-2.128.438-.737 1.536-.98 2.275-.508l.88.56M12 3v3m0 12v3" />
                    </svg>
                </div>
            </div>
            <div class="mt-2.5">
                <span class="text-xs text-slate-400">Akumulasi hutang yang belum dibayarkan</span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="flex space-x-6" aria-label="Tabs">
            <a href="{{ route('pembelian.index', ['tab' => 'semua']) }}"
               class="{{ $tab === 'semua' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300' }} whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Semua Nota
            </a>
            <a href="{{ route('pembelian.index', ['tab' => 'belum_lunas']) }}"
               class="{{ $tab === 'belum_lunas' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:hover:text-slate-300' }} whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold transition-all">
                Belum Lunas
            </a>
        </nav>
    </div>

    <!-- Purchase Orders Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        @if ($pembelians->isEmpty())
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H3.75m0 18.75h16.5V6H3.75v15Z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Tidak ada data nota pembelian.</p>
                <p class="text-xs text-slate-500 mt-0.5">Daftar nota pembelian Anda akan muncul di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No. Faktur</th>
                            <th class="px-6 py-4">Supplier</th>
                            <th class="px-6 py-4">Tanggal Faktur</th>
                            <th class="px-6 py-4">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        @foreach ($pembelians as $pembelian)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900 dark:text-white">
                                    {{ $pembelian->nomor_faktur }}
                                    @if ($pembelian->nomor_po)
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">PO: {{ $pembelian->nomor_po }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                    {{ $pembelian->supplier->nama_supplier }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $pembelian->tanggal_faktur->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($pembelian->tanggal_jatuh_tempo)
                                        <span class="{{ $pembelian->status_bayar === 'Belum Lunas' && $pembelian->tanggal_jatuh_tempo->isPast() ? 'text-red-600 dark:text-red-400 font-bold' : '' }}">
                                            {{ $pembelian->tanggal_jatuh_tempo->translatedFormat('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-semibold text-slate-900 dark:text-white">
                                    {{ formatRupiah($pembelian->total) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($pembelian->status_bayar === 'Lunas')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/50">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-200/50 dark:border-red-900/50">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pembelian.show', $pembelian->id) }}"
                                           class="px-2.5 py-1 text-xs font-medium border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm"
                                           title="Lihat Detail">
                                            Detail
                                        </a>
                                        @role('admin')
                                        @if ($pembelian->status_bayar === 'Belum Lunas')
                                            <a href="{{ route('pembelian.show', $pembelian->id) }}#pembayaran"
                                               class="px-2.5 py-1 text-xs font-semibold bg-red-50 hover:bg-red-100 dark:bg-red-950/30 dark:hover:bg-red-900/40 text-red-700 dark:text-red-300 rounded-lg transition-colors border border-red-200/50 dark:border-red-900/50 shadow-sm"
                                               title="Bayar Hutang">
                                                Bayar
                                            </a>
                                        @endif
                                        @endrole
                                    </div>
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
