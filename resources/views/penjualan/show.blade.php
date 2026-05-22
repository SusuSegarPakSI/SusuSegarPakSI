@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <nav aria-label="Breadcrumb" class="mb-2">
                <ol class="flex items-center gap-1.5 text-xs text-slate-500">
                    <li><a href="{{ route('penjualan.index') }}" class="hover:text-indigo-600 transition-colors">Penjualan</a></li>
                    <li>
                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-slate-700 dark:text-slate-300 font-medium">Detail Transaksi</li>
                </ol>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50 font-mono">{{ $penjualan->nomor_transaksi }}</h1>
                
                <!-- Status Badge -->
                @if($penjualan->status === 'Lunas')
                <span class="inline-flex items-center text-xs font-semibold text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 px-2.5 py-0.5 rounded-full">
                    Lunas
                </span>
                @elseif($penjualan->status === 'Retur Sebagian')
                <span class="inline-flex items-center text-xs font-semibold text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-900/50 px-2.5 py-0.5 rounded-full">
                    Retur Sebagian
                </span>
                @else
                <span class="inline-flex items-center text-xs font-semibold text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-950/30 border border-red-100 dark:border-red-900/50 px-2.5 py-0.5 rounded-full">
                    Retur Penuh
                </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('penjualan.cetak', $penjualan->id) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.618 0-1.113-.487-1.12-1.106L6 18m11.66 0H6.34m9.68-14.333H7.98m7.7 0a48.536 48.536 0 0 1 2.3 2.288c.086.092.107.223.053.336a10.024 10.024 0 0 1-.95 1.583m-10.8 0a10.025 10.025 0 0 1-.95-1.583.344.344 0 0 1 .054-.336 48.574 48.574 0 0 1 2.3-2.288m7.7 0H7.98m0 0L6.72 8.357m1.26-4.5H16.02L17.28 8.357M7.98 3.827H16.02m-.02 4.53H8M17.66 18v-3.07a9.07 9.07 0 0 0-1.424-4.887l-.02-.03a9.07 9.07 0 0 0-6.432-3.83" />
                </svg>
                Cetak Struk
            </a>

            @role('admin')
                @if($penjualan->status !== 'Retur Penuh')
                <a href="#retur-section" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-950/30 rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Proses Retur
                </a>
                @endif
            @endrole
        </div>
    </div>

    <!-- Error/Validation alerts -->
    @if ($errors->any())
    <div class="p-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 rounded-xl flex items-start gap-3 text-red-700 dark:text-red-400" role="alert">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <div class="text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Content Layout: grid of 2 columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main invoice table details (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Table Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Daftar Barang Belanja</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                <th class="py-3 px-6">Produk</th>
                                <th class="py-3 px-6 text-right">Harga Satuan</th>
                                <th class="py-3 px-6 text-center">Kuantitas</th>
                                <th class="py-3 px-6 text-center">Telah Diretur</th>
                                <th class="py-3 px-6 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($penjualan->details as $detail)
                            <tr class="text-slate-700 dark:text-slate-300">
                                <td class="py-3.5 px-6">
                                    <div class="font-medium text-slate-900 dark:text-white">{{ $detail->produk->nama_produk }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $detail->produk->kode_produk }}</div>
                                </td>
                                <td class="py-3.5 px-6 text-right font-mono text-xs">{{ $detail->harga_satuan_formatted }}</td>
                                <td class="py-3.5 px-6 text-center font-mono text-xs">{{ $detail->kuantitas }}</td>
                                <td class="py-3.5 px-6 text-center font-mono text-xs">
                                    @php
                                        $returnedQty = $alreadyReturned[$detail->produk_id] ?? 0;
                                    @endphp
                                    <span class="{{ $returnedQty > 0 ? 'text-red-500 font-bold' : 'text-slate-400' }}">
                                        {{ $returnedQty }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-right font-mono text-xs text-slate-900 dark:text-white font-semibold">{{ $detail->subtotal_formatted }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Returns History Card (If there are any returns) -->
            @if($penjualan->returs->count() > 0)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-red-50/50 dark:bg-red-950/10">
                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-400">Riwayat Retur Penjualan</h3>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($penjualan->returs as $retur)
                    <div class="border border-slate-200 dark:border-slate-800 rounded-lg p-4 bg-slate-50 dark:bg-slate-900/50 text-xs">
                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2 mb-2">
                            <span class="font-medium text-slate-500">Tanggal Retur: <span class="text-slate-900 dark:text-white font-bold font-mono">{{ $retur->tanggal_retur->format('d/m/Y') }}</span></span>
                            <span class="font-semibold text-red-600 dark:text-red-400 font-mono">Nilai Retur: {{ $retur->total_nilai_retur_formatted }}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            <p class="text-slate-500">Alasan: <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $retur->alasan }}</span></p>
                            <p class="text-slate-500">Restorasi Stok: 
                                <span class="px-1.5 py-0.5 rounded {{ $retur->kembalikan_stok ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-200 text-slate-800' }} font-bold">
                                    {{ $retur->kembalikan_stok ? 'Ya (Kembalikan ke Stok)' : 'Tidak' }}
                                </span>
                            </p>
                        </div>
                        <!-- Retur detail table -->
                        <div class="border border-slate-200 dark:border-slate-800 rounded-md overflow-hidden bg-white dark:bg-slate-900">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-100 dark:bg-slate-800 text-[10px] text-slate-500 font-semibold uppercase">
                                    <tr>
                                        <th class="p-2">Produk</th>
                                        <th class="p-2 text-center">Jumlah Diretur</th>
                                        <th class="p-2 text-right">Harga Satuan</th>
                                        <th class="p-2 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach($retur->details as $rDetail)
                                    <tr class="text-[11px] text-slate-600 dark:text-slate-400">
                                        <td class="p-2 font-medium text-slate-900 dark:text-white">{{ $rDetail->produk->nama_produk }}</td>
                                        <td class="p-2 text-center font-mono font-bold text-red-500">{{ $rDetail->kuantitas_retur }}</td>
                                        <td class="p-2 text-right font-mono">{{ $rDetail->harga_satuan_formatted }}</td>
                                        <td class="p-2 text-right font-mono font-semibold">{{ $rDetail->subtotal_formatted }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Info & summary totals (1 col) -->
        <div class="space-y-6">
            <!-- Transaction Metadata Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50 border-b border-slate-100 dark:border-slate-800 pb-3">Metadata Transaksi</h3>
                
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Tanggal Nota</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 font-mono">{{ $penjualan->tanggal->format('d F Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Pelanggan</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">
                            {{ $penjualan->customer ? $penjualan->customer->nama : 'Pelanggan Umum' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Kasir / Operator</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $penjualan->creator ? $penjualan->creator->name : '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Metode Bayar</span>
                        <span class="inline-flex items-center text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700">
                            {{ $penjualan->metode_bayar }}
                        </span>
                    </div>
                </div>

                @if($penjualan->catatan)
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-1">
                    <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Catatan</span>
                    <p class="text-xs text-slate-600 dark:text-slate-400 italic">"{{ $penjualan->catatan }}"</p>
                </div>
                @endif
            </div>

            <!-- Financial Totals Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-5 shadow-sm space-y-3 bg-slate-50/50">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50 border-b border-slate-100 dark:border-slate-800 pb-3">Ringkasan Nilai</h3>
                
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Subtotal Belanja</span>
                        <span class="font-mono">{{ $penjualan->subtotal_formatted }}</span>
                    </div>

                    <div class="flex items-center justify-between text-slate-500">
                        <span>Diskon Nominal</span>
                        <span class="font-mono text-red-500">- {{ $penjualan->diskon_nominal_formatted }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-slate-800 text-base font-bold text-slate-900 dark:text-slate-50">
                        <span>Grand Total</span>
                        <span class="font-mono text-indigo-600 dark:text-indigo-400 text-lg">{{ $penjualan->grand_total_formatted }}</span>
                    </div>

                    <div class="flex items-center justify-between text-slate-500 pt-1">
                        <span>Jumlah Bayar</span>
                        <span class="font-mono">{{ $penjualan->jumlah_bayar_formatted }}</span>
                    </div>

                    <div class="flex items-center justify-between text-slate-500">
                        <span>Uang Kembali</span>
                        <span class="font-mono font-medium text-slate-700 dark:text-slate-300">{{ $penjualan->kembalian_formatted }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin-only POS Return Panel -->
    @role('admin')
        @if($penjualan->status !== 'Retur Penuh')
        <div id="retur-section" class="bg-white dark:bg-slate-900 border border-red-200 dark:border-red-900/50 rounded-xl shadow-md overflow-hidden scroll-mt-6 transition-colors">
            <div class="px-6 py-4 border-b border-red-200 dark:border-red-900/50 bg-red-50/50 dark:bg-red-950/10">
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Formulir Retur Penjualan POS
                </h3>
                <p class="text-xs text-red-500/80 mt-0.5">Lengkapi formulir di bawah untuk mengembalikan produk transaksi ini.</p>
            </div>
            
            <form method="POST" action="{{ route('penjualan.retur', $penjualan->id) }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Tanggal Retur -->
                    <div class="space-y-1.5">
                        <label for="tanggal_retur" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Tanggal Retur</label>
                        <input type="date" id="tanggal_retur" name="tanggal_retur" value="{{ date('Y-m-d') }}" required class="block w-full py-2 px-3 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <!-- Alasan Retur -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="alasan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Alasan Retur</label>
                        <input type="text" id="alasan" name="alasan" placeholder="Masukkan alasan pengembalian barang secara lengkap..." required class="block w-full py-2 px-3 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Stock Return Switch -->
                <div class="flex items-center gap-3">
                    <!-- Toggle Switch with Alpine.js or native checkbox -->
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="kembalikan_stok" value="1" checked class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-red-600 focus:ring-red-500 focus:ring-offset-0 bg-white dark:bg-slate-900 cursor-pointer">
                        <div>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-slate-100">Pulihkan Stok Produk</p>
                            <p class="text-xs text-slate-400">Centang opsi ini untuk mengembalikan jumlah barang yang diretur ke persediaan stok aktif produk.</p>
                        </div>
                    </label>
                </div>

                <!-- Item Return Grid Selectors -->
                <div class="border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-semibold uppercase">
                                <th class="p-3">Nama Produk</th>
                                <th class="p-3 text-center">Beli (Qty)</th>
                                <th class="p-3 text-center">Telah Diretur</th>
                                <th class="p-3 text-center">Bisa Diretur</th>
                                <th class="p-3 text-right">Kuantitas Retur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($penjualan->details as $detail)
                            @php
                                $returnedQty = $alreadyReturned[$detail->produk_id] ?? 0;
                                $maxReturQty = $detail->kuantitas - $returnedQty;
                            @endphp
                            <tr class="text-slate-700 dark:text-slate-300 {{ $maxReturQty === 0 ? 'bg-slate-50/50 dark:bg-slate-800/10 opacity-60' : '' }}">
                                <td class="p-3">
                                    <div class="font-medium text-slate-900 dark:text-white">{{ $detail->produk->nama_produk }}</div>
                                    <div class="text-[9px] font-mono text-slate-400 mt-0.5">{{ $detail->produk->kode_produk }}</div>
                                </td>
                                <td class="p-3 text-center font-mono">{{ $detail->kuantitas }}</td>
                                <td class="p-3 text-center font-mono text-red-500 font-medium">{{ $returnedQty }}</td>
                                <td class="p-3 text-center font-mono font-bold text-slate-800 dark:text-slate-200">{{ $maxReturQty }}</td>
                                <td class="p-3 text-right">
                                    @if($maxReturQty > 0)
                                    <div class="inline-block w-24">
                                        <input type="number" name="items[{{ $detail->produk_id }}]" min="0" max="{{ $maxReturQty }}" placeholder="0" class="block w-full py-1 px-2.5 text-right font-mono border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded focus:outline-none focus:ring-1 focus:ring-red-500">
                                    </div>
                                    @else
                                    <span class="text-red-500 font-bold uppercase text-[10px]">Penuh</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Submit actions -->
                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-sm transition-colors focus:outline-none">
                        Proses Retur Penjualan
                    </button>
                </div>
            </form>
        </div>
        @endif
    @endrole
</div>
@endsection
