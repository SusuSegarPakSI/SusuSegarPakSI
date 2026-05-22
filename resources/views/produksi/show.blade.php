@extends('layouts.app')

@section('title', 'Detail Batch Produksi ' . $produksi->nomor_produksi)
@section('breadcrumb', 'Produksi / Detail')

@section('content')
<div class="max-w-4xl mx-auto" x-data="showPageManager()">
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('produksi.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="flex items-center gap-3 mt-2">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white font-mono">{{ $produksi->nomor_produksi }}</h1>
                <span class="text-sm font-semibold text-slate-400 dark:text-slate-500">|</span>
                <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ $produksi->nama_batch }}</span>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Dibuat oleh: <span class="font-semibold text-slate-600 dark:text-slate-300">{{ $produksi->creator->name }}</span> • {{ $produksi->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Right Side: Status Badge -->
        <div>
            @if($produksi->status === 'Draft')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                    Status: Draft
                </span>
            @elseif($produksi->status === 'Proses')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-800 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-900/30">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Sedang Diproses
                </span>
            @elseif($produksi->status === 'Selesai')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/30">
                    Selesai
                </span>
            @elseif($produksi->status === 'Dibatalkan')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-400 border border-rose-200/50 dark:border-rose-900/30">
                    Dibatalkan
                </span>
            @endif
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/30 rounded-xl text-sm text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/30 rounded-xl text-sm text-rose-800 dark:text-rose-400 flex items-start gap-2.5">
            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div>
                <span class="font-semibold block mb-1">Gagal Memproses Batch:</span>
                <span class="text-xs">{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <!-- Stepper Progress Tracker -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-5 mb-6">
        <div class="flex items-center justify-between max-w-lg mx-auto">
            <!-- Step 1: Draft -->
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-950/40">
                    ✓
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider mt-2 text-indigo-600 dark:text-indigo-400">Draft</span>
            </div>

            <!-- Line 1-2 -->
            <div class="flex-1 h-0.5 mx-4" 
                 :class="{{ in_array($produksi->status, ['Proses', 'Selesai']) ? 'true' : 'false' }} ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-800'"></div>

            <!-- Step 2: Proses -->
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
                     :class="{{ in_array($produksi->status, ['Proses', 'Selesai']) ? 'true' : 'false' }} ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                    @if(in_array($produksi->status, ['Selesai'])) ✓ @else 2 @endif
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider mt-2" 
                      :class="{{ in_array($produksi->status, ['Proses', 'Selesai']) ? 'true' : 'false' }} ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">Proses</span>
            </div>

            <!-- Line 2-3 -->
            <div class="flex-1 h-0.5 mx-4" 
                 :class="{{ $produksi->status === 'Selesai' ? 'true' : 'false' }} ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-800'"></div>

            <!-- Step 3: Selesai / Dibatalkan -->
            <div class="flex flex-col items-center">
                @if($produksi->status === 'Dibatalkan')
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-rose-600 text-white ring-4 ring-rose-100 dark:ring-rose-950/40">
                        ✕
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider mt-2 text-rose-600 dark:text-rose-400">Dibatalkan</span>
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
                         :class="{{ $produksi->status === 'Selesai' ? 'true' : 'false' }} ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 dark:ring-emerald-950/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                        @if($produksi->status === 'Selesai') ✓ @else 3 @endif
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider mt-2" 
                          :class="{{ $produksi->status === 'Selesai' ? 'true' : 'false' }} ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">Selesai</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Details Card Container -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden mb-6" x-data="{ tab: 'biaya' }">
        <!-- Tabs Header -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
            <button 
                @click="tab = 'biaya'"
                :class="tab === 'biaya' ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="flex-1 py-3 px-4 text-xs font-bold uppercase tracking-wider border-b-2 text-center transition-all focus:outline-none"
            >
                Rincian Biaya
            </button>
            <button 
                @click="tab = 'bahan'"
                :class="tab === 'bahan' ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="flex-1 py-3 px-4 text-xs font-bold uppercase tracking-wider border-b-2 text-center transition-all focus:outline-none"
            >
                Bahan Terpakai
            </button>
            <button 
                @click="tab = 'hpp'"
                :class="tab === 'hpp' ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="flex-1 py-3 px-4 text-xs font-bold uppercase tracking-wider border-b-2 text-center transition-all focus:outline-none"
            >
                Hasil & HPP
            </button>
        </div>

        <!-- Tab 1: Rincian Biaya -->
        <div x-show="tab === 'biaya'" class="p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Komponen Biaya Pengolahan</h3>
            
            <div class="overflow-x-auto border border-slate-200 dark:border-slate-800 rounded-xl">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-4 py-3">Jenis Biaya</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 dark:divide-slate-800">
                        @php $totalB = 0; @endphp
                        @foreach($produksi->biayas as $biaya)
                            @php $totalB += $biaya->nominal; @endphp
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/10 transition-colors">
                                <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white capitalize">
                                    {{ str_replace('_', ' ', $biaya->jenis_biaya) }}
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $biaya->keterangan }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-semibold text-slate-900 dark:text-white">
                                    Rp {{ number_format($biaya->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 dark:bg-slate-800/30 font-bold border-t border-slate-200 dark:border-slate-800 text-sm">
                            <td colspan="2" class="px-4 py-4 text-slate-900 dark:text-white">Total Biaya Produksi:</td>
                            <td class="px-4 py-4 text-right font-mono text-indigo-600 dark:text-indigo-400 text-base">
                                Rp {{ number_format($totalB, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Tab 2: Bahan Terpakai -->
        <div x-show="tab === 'bahan'" class="p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Snapshotted Pemakaian Bahan Baku</h3>
            
            <div class="overflow-x-auto border border-slate-200 dark:border-slate-800 rounded-xl">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-4 py-3">Nama Bahan Baku</th>
                            <th class="px-4 py-3">Kuantitas Pakai</th>
                            <th class="px-4 py-3 font-mono">Harga Satuan Saat Itu</th>
                            <th class="px-4 py-3 text-right font-mono">Total Biaya Snapshot</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 dark:divide-slate-800">
                        @forelse($produksi->bahanPakais as $pakai)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/10 transition-colors">
                                <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                    {{ $pakai->bahanBaku->nama_bahan }}
                                </td>
                                <td class="px-4 py-3 font-mono text-slate-600 dark:text-slate-400">
                                    {{ number_format($pakai->kuantitas_pakai, 2, ',', '.') }} {{ $pakai->bahanBaku->satuan }}
                                </td>
                                <td class="px-4 py-3 font-mono text-slate-600 dark:text-slate-400">
                                    Rp {{ number_format($pakai->harga_satuan_saat_itu, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-semibold text-slate-900 dark:text-white">
                                    Rp {{ number_format($pakai->kuantitas_pakai * $pakai->harga_satuan_saat_itu, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
                                    Tidak ada data pemakaian bahan baku yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Hasil & HPP -->
        <div x-show="tab === 'hpp'" class="p-6 space-y-6">
            <!-- Output Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 dark:bg-slate-800/30 border border-slate-200 dark:border-slate-800 rounded-xl space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Perbandingan Output</span>
                    <div class="text-base font-bold text-slate-900 dark:text-white flex items-baseline gap-2">
                        <span class="font-mono">{{ $produksi->target_output }}</span>
                        <span class="text-xs text-slate-400">Target</span>
                        <span class="text-slate-300">/</span>
                        <span class="font-mono text-indigo-600 dark:text-indigo-400">{{ $produksi->actual_output ?: '-' }}</span>
                        <span class="text-xs text-indigo-500 dark:text-indigo-400">Aktual</span>
                    </div>
                </div>

                <div class="p-4 bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/50 dark:border-amber-900/30 rounded-xl space-y-1">
                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider">HPP Manufaktur Per Unit</span>
                    <div class="text-lg font-bold text-amber-800 dark:text-amber-300 font-mono">
                        @if($produksi->hpp_per_unit)
                            Rp {{ number_format($produksi->hpp_per_unit, 0, ',', '.') }}
                        @else
                            <span class="text-slate-400 italic font-sans text-xs">Belum dihitung (Menunggu Selesai)</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Catatan Tambahan</h4>
                <div class="p-3 bg-slate-50 dark:bg-slate-800/20 rounded-lg text-sm text-slate-700 dark:text-slate-300 italic">
                    {{ $produksi->catatan ?: 'Tidak ada catatan tambahan untuk batch ini.' }}
                </div>
            </div>

            <!-- Moving Average Formula Detail -->
            @if($produksi->status === 'Selesai')
                <div class="p-4 bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-200/50 dark:border-indigo-900/30 rounded-xl space-y-2">
                    <div class="flex items-center gap-2 text-xs font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 11.026.041v.018zm-2.25 0h.008v.008H9v-.008zm1.5-4.875c-.621 0-1.125-.504-1.125-1.125s.504-1.125 1.125-1.125 1.125.504 1.125 1.125-.504 1.125-1.125 1.125zM9.75 17.25h4.5M12 13.5v-3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Info Kalkulasi Moving Average
                    </div>
                    <p class="text-xs text-indigo-750 dark:text-indigo-400 leading-relaxed">
                        Saat diselesaikan, biaya pokok produk jadi ini diperbarui secara otomatis menggunakan metode <strong>Moving Average (3 batch selesai terakhir)</strong> untuk menjamin harga live produk terhitung secara presisi dan adil terhadap fluktuasi bahan baku di pasar.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Active Operational Action Panel (Admin Only) -->
    @role('admin')
        @if(in_array($produksi->status, ['Draft', 'Proses']))
            <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tindakan Operasional</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 block">Selesaikan batch atau batalkan produksi untuk mengembalikan stok.</span>
                </div>

                <div class="flex gap-3 w-full sm:w-auto shrink-0 justify-end">
                    <!-- Action: Cancel Batch -->
                    <button 
                        type="button" 
                        @click="openCancelModal()"
                        class="w-full sm:w-auto px-4 py-2 border border-rose-200 dark:border-rose-900/50 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-xs font-bold rounded-lg text-rose-600 dark:text-rose-400 transition-colors"
                    >
                        Batalkan Batch
                    </button>

                    <!-- Action: Start Batch (If Draft) -->
                    @if($produksi->status === 'Draft')
                        <form action="{{ route('produksi.mulai', $produksi->id) }}" method="POST" class="inline">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full sm:w-auto px-5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-xs font-bold text-white shadow-sm rounded-lg transition-colors"
                            >
                                Mulai Produksi (Proses)
                            </button>
                        </form>
                    @endif

                    <!-- Action: Complete Batch (If Proses) -->
                    @if($produksi->status === 'Proses')
                        <button 
                            type="button" 
                            @click="openCompleteModal()"
                            class="w-full sm:w-auto px-5 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-xs font-bold text-white shadow-sm rounded-lg transition-colors"
                        >
                            Selesaikan & Hitung HPP
                        </button>
                    @endif
                </div>
            </div>
        @endif
    @endrole

    <!-- Modal Complete Batch Input (Admin Only) -->
    @role('admin')
    <div 
        x-show="isCompleteModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/60 dark:bg-slate-950/80 backdrop-blur-sm" @click="closeCompleteModal()"></div>

        <!-- Modal Container -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden transition-all transform"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Selesaikan Batch Produksi</h3>
                    <button @click="closeCompleteModal()" class="text-slate-400 hover:text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('produksi.selesaikan', $produksi->id) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Masukkan jumlah output produk jadi aktual yang berhasil dihasilkan pada batch ini untuk menghitung harga pokok produksi (HPP) akhir.
                        </p>

                        <div>
                            <label for="actual_output" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Jumlah Output Aktual (Unit)</label>
                            <input 
                                type="number" 
                                id="actual_output" 
                                name="actual_output" 
                                value="{{ $produksi->target_output }}" 
                                required
                                min="1"
                                class="w-full text-sm font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Target produksi awal adalah: <strong class="font-mono text-slate-700 dark:text-slate-300">{{ $produksi->target_output }} Unit</strong></p>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                        <button 
                            type="button" 
                            @click="closeCompleteModal()"
                            class="px-4 py-2 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 rounded-lg text-xs font-semibold text-slate-750 dark:text-slate-300 transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm rounded-lg text-xs font-semibold transition-colors"
                        >
                            Selesaikan & Hitung HPP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cancel Confirm (Admin Only) -->
    <div 
        x-show="isCancelModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/60 dark:bg-slate-950/80 backdrop-blur-sm" @click="closeCancelModal()"></div>

        <!-- Modal Container -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-sm overflow-hidden transition-all transform"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div class="p-6 text-center space-y-4">
                    <div class="w-12 h-12 rounded-full bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Batalkan Batch Produksi?</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Apakah Anda yakin ingin membatalkan batch ini? Seluruh stok bahan baku yang telah didecrement akan dikembalikan secara utuh ke gudang persediaan.
                        </p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-200 dark:border-slate-800 flex justify-center gap-3">
                    <button 
                        type="button" 
                        @click="closeCancelModal()"
                        class="px-4 py-2 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 rounded-lg text-xs font-semibold text-slate-750 dark:text-slate-300 transition-colors"
                    >
                        Tidak, Kembali
                    </button>

                    <form action="{{ route('produksi.batalkan', $produksi->id) }}" method="POST">
                        @csrf
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white shadow-sm rounded-lg text-xs font-semibold transition-colors"
                        >
                            Ya, Batalkan Batch
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endrole
</div>

<script>
    function showPageManager() {
        return {
            isCompleteModalOpen: false,
            isCancelModalOpen: false,

            openCompleteModal() {
                this.isCompleteModalOpen = true;
            },

            closeCompleteModal() {
                this.isCompleteModalOpen = false;
            },

            openCancelModal() {
                this.isCancelModalOpen = true;
            },

            closeCancelModal() {
                this.isCancelModalOpen = false;
            }
        };
    }
</script>
@endsection
