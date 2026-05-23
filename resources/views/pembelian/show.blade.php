@extends('layouts.app')

@section('title', 'Detail Pembelian #' . $pembelian->nomor_faktur)
@section('breadcrumb', 'Pembelian / Detail')

@section('content')
<div class="space-y-6" x-data="{ showPayModal: false }">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('pembelian.index') }}" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white font-mono">Faktur: {{ $pembelian->nomor_faktur }}</h1>
                    @if ($pembelian->status_bayar === 'Lunas')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/50">
                            Lunas
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-200/50 dark:border-red-900/50">
                            Belum Lunas
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1">Dicatat oleh {{ $pembelian->creator->name ?? 'Sistem' }} pada {{ $pembelian->created_at->translatedFormat('d F Y H:i') }}</p>
            </div>
        </div>
        @role('admin')
        @if ($pembelian->status_bayar === 'Belum Lunas')
            <div class="shrink-0">
                <button type="button" @click="showPayModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.559c.74.47 1.837.227 2.275-.51l.001-.003c.437-.743.205-1.72-.549-2.127l-.082-.045c-.744-.407-.978-1.383-.54-2.128.438-.737 1.536-.98 2.275-.508l.88.56M12 3v3m0 12v3" />
                    </svg>
                    Catat Pembayaran
                </button>
            </div>
        @endif
        @endrole
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- DETAIL FAKTUR (Kiri 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Table Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Rincian Item Pembelian</h3>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/20 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3.5">Kode</th>
                            <th class="px-6 py-3.5">Nama Bahan</th>
                            <th class="px-6 py-3.5 text-center">Kuantitas</th>
                            <th class="px-6 py-3.5 text-right">Harga Beli</th>
                            <th class="px-6 py-3.5 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        @foreach ($pembelian->details as $detail)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-900 dark:text-white">
                                    {{ $detail->bahanBaku->kode_bahan }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                    {{ $detail->bahanBaku->nama_bahan }}
                                </td>
                                <td class="px-6 py-4 text-center font-mono">
                                    {{ number_format($detail->kuantitas) }} <span class="text-xs text-slate-400 font-sans ml-0.5">{{ $detail->bahanBaku->satuan }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono">
                                    {{ formatRupiah($detail->harga_satuan) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-semibold text-slate-900 dark:text-white">
                                    {{ formatRupiah($detail->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 dark:bg-slate-900/20 border-t border-slate-200 dark:border-slate-800">
                            <td colspan="4" class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-slate-300 text-right uppercase">Total Faktur</td>
                            <td class="px-6 py-4 text-right font-mono font-extrabold text-indigo-600 dark:text-indigo-400 text-sm">
                                {{ formatRupiah($pembelian->total) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- RIWAYAT PEMBAYARAN -->
            <div id="pembayaran" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Riwayat Pembayaran Hutang</h3>
                </div>
                @if ($pembelian->pembayaranHutangs->isEmpty())
                    <div class="py-6 flex flex-col items-center justify-center text-center">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Belum ada riwayat pembayaran yang dicatat.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/20 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-3">Tanggal Bayar</th>
                                <th class="px-6 py-3">Metode</th>
                                <th class="px-6 py-3">Catatan</th>
                                <th class="px-6 py-3 text-right">Jumlah Bayar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                            @foreach ($pembelian->pembayaranHutangs as $payment)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors">
                                    <td class="px-6 py-3.5 font-medium">
                                        {{ $payment->tanggal_bayar->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3.5 font-semibold text-indigo-600 dark:text-indigo-400">
                                        {{ $payment->metode }}
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-500">
                                        {{ $payment->catatan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono font-bold text-slate-900 dark:text-white">
                                        {{ formatRupiah($payment->jumlah_bayar) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 dark:bg-slate-900/20 border-t border-slate-200 dark:border-slate-800">
                                <td colspan="3" class="px-6 py-3 text-xs font-semibold text-slate-700 dark:text-slate-300 text-right uppercase">Total Pembayaran</td>
                                <td class="px-6 py-3 text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400">
                                    {{ formatRupiah($pembelian->pembayaranHutangs->sum('jumlah_bayar')) }}
                                </td>
                            </tr>
                            <tr class="bg-slate-100 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                                <td colspan="3" class="px-6 py-3 text-xs font-bold text-slate-900 dark:text-white text-right uppercase">Sisa Hutang</td>
                                <td class="px-6 py-3 text-right font-mono font-extrabold text-red-650 dark:text-red-400">
                                    {{ formatRupiah($pembelian->total - $pembelian->pembayaranHutangs->sum('jumlah_bayar')) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        </div>

        <!-- HEADER PANEL / METADATA (Kanan 1/3) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">Informasi Faktur</h3>

                <div class="space-y-3.5 text-xs">
                    <!-- Supplier -->
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-slate-400 shrink-0">Supplier:</span>
                        <div class="text-right">
                            <p class="font-bold text-slate-900 dark:text-white">{{ $pembelian->supplier->nama_supplier }}</p>
                            <p class="text-[10px] font-mono text-slate-450 mt-0.5">{{ $pembelian->supplier->kode_supplier }}</p>
                        </div>
                    </div>

                    <!-- Tanggal Faktur -->
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal Faktur:</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $pembelian->tanggal_faktur->translatedFormat('d F Y') }}</span>
                    </div>

                    <!-- Tanggal Jatuh Tempo -->
                    <div class="flex justify-between">
                        <span class="text-slate-400">Jatuh Tempo:</span>
                        @if ($pembelian->tanggal_jatuh_tempo)
                            <span class="font-medium text-slate-900 dark:text-white">{{ $pembelian->tanggal_jatuh_tempo->translatedFormat('d F Y') }}</span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </div>

                    <!-- Nomor PO -->
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nomor PO:</span>
                        <span class="font-mono font-medium text-slate-900 dark:text-white">{{ $pembelian->nomor_po ?? '-' }}</span>
                    </div>

                    <!-- Catatan -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">Catatan:</span>
                        <p class="text-slate-600 dark:text-slate-400 italic bg-slate-50 dark:bg-slate-850 p-2.5 rounded-lg border border-slate-150 dark:border-slate-800">{{ $pembelian->catatan ?? 'Tidak ada catatan.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAY DEBT MODAL -->
    @role('admin')
    @if ($pembelian->status_bayar === 'Belum Lunas')
        <div x-show="showPayModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showPayModal = false"></div>

            <!-- Position Wrapper -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-slate-900 text-left shadow-xl border border-slate-200 dark:border-slate-800 transition-all sm:my-8 sm:w-full sm:max-w-md p-6"
                     x-trap="showPayModal">
                    
                    <div class="flex items-start gap-4">
                        <div class="mx-auto flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H3m0 2.25v3.75m0-3.75h1.5a.75.75 0 0 1 .75.75v3.75m-3 0h3.75m-3.75 0v3.75m0-3.75h1.5a.75.75 0 0 1 .75.75v3.75m-3 0h3.75M21 12v3.75m0-3.75h-1.5a.75.75 0 0 0-.75.75v3.75m3 0H17.25m3.75 0v3.75M21 6h.75m0 0v3.75m0-3.75h-1.5a.75.75 0 0 0-.75.75v3.75m3 0H17.25" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Catat Pembayaran Hutang</h3>
                            <p class="mt-1 text-xs text-slate-500">Nota Faktur: <span class="font-mono font-bold">{{ $pembelian->nomor_faktur }}</span></p>

                            <!-- Form -->
                            <form action="{{ route('pembelian.bayar', $pembelian->id) }}" method="POST" class="mt-4 space-y-4">
                                @csrf
                                <!-- Sisa Hutang Info -->
                                <div class="bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/50 p-3 rounded-lg text-xs flex justify-between items-center text-red-800 dark:text-red-400">
                                    <span class="font-medium uppercase tracking-wider">Sisa Hutang:</span>
                                    <span class="font-mono font-bold text-sm">{{ formatRupiah($pembelian->total - $pembelian->pembayaranHutangs->sum('jumlah_bayar')) }}</span>
                                </div>

                                <!-- Tanggal Bayar -->
                                <div class="text-left space-y-1">
                                    <label for="tanggal_bayar" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                                    <input type="date" id="tanggal_bayar" name="tanggal_bayar" required value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                           class="block w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                </div>

                                <!-- Jumlah Bayar -->
                                <div class="text-left space-y-1">
                                    <label for="jumlah_bayar" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Bayar (Rupiah) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs">Rp</span>
                                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" required min="1" max="{{ $pembelian->total - $pembelian->pembayaranHutangs->sum('jumlah_bayar') }}" value="{{ $pembelian->total - $pembelian->pembayaranHutangs->sum('jumlah_bayar') }}"
                                               class="block w-full pl-8 pr-3 py-2 text-xs font-mono bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                    </div>
                                </div>

                                <!-- Metode Pembayaran -->
                                <div class="text-left space-y-1">
                                    <label for="metode" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode Pembayaran <span class="text-red-500">*</span></label>
                                    <select id="metode" name="metode" required
                                            class="block w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="Kas / Tunai">Kas / Tunai</option>
                                        <option value="Cek / Giro">Cek / Giro</option>
                                    </select>
                                </div>

                                <!-- Catatan -->
                                <div class="text-left space-y-1">
                                    <label for="catatan_pay" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Catatan <span class="text-xs text-slate-400">(Opsional)</span></label>
                                    <textarea id="catatan_pay" name="catatan" rows="2" placeholder="Masukkan nomor bukti transfer, nama rekening, dll..."
                                              class="block w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"></textarea>
                                </div>

                                <!-- Actions -->
                                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                                    <button type="button" @click="showPayModal = false"
                                            class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Catat Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endrole
</div>
@endsection
