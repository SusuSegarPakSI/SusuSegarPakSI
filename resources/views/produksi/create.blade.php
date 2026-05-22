@extends('layouts.app')

@section('title', 'Mulai Batch Produksi')
@section('breadcrumb', 'Produksi / Baru')

@section('content')
<div class="max-w-5xl mx-auto" x-data="productionWizard()">
    <!-- Header Page -->
    <div class="mb-6">
        <a href="{{ route('produksi.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-2">Mulai Batch Produksi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Ikuti 3 langkah mudah untuk merumuskan biaya produksi dan menghitung estimasi HPP.</p>
    </div>

    <!-- Alert Stock Error Backend -->
    @if($errors->has('stok') || $errors->has('produk_output_id'))
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/30 rounded-xl text-sm text-rose-800 dark:text-rose-400 flex items-start gap-2.5">
            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div>
                <span class="font-semibold block mb-1">Gagal Menyiapkan Batch:</span>
                <span class="text-xs">{{ $errors->first('stok') ?: $errors->first('produk_output_id') }}</span>
            </div>
        </div>
    @endif

    <!-- Step Progress Stepper Bar -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-4 mb-6">
        <div class="flex items-center justify-between max-w-lg mx-auto">
            <!-- Step 1 Indicator -->
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
                     :class="step >= 1 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-950/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                    1
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider mt-2" :class="step >= 1 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">Info Batch</span>
            </div>

            <!-- Line 1-2 -->
            <div class="flex-1 h-0.5 bg-slate-200 dark:bg-slate-800 mx-4" :class="step >= 2 ? 'bg-indigo-600' : ''"></div>

            <!-- Step 2 Indicator -->
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
                     :class="step >= 2 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-950/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                    2
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider mt-2" :class="step >= 2 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">Rincian Biaya</span>
            </div>

            <!-- Line 2-3 -->
            <div class="flex-1 h-0.5 bg-slate-200 dark:bg-slate-800 mx-4" :class="step >= 3 ? 'bg-indigo-600' : ''"></div>

            <!-- Step 3 Indicator -->
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
                     :class="step >= 3 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-950/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                    3
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider mt-2" :class="step >= 3 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">Review & Mulai</span>
            </div>
        </div>
    </div>

    <!-- Dynamic Steps Form -->
    <form action="{{ route('produksi.store') }}" method="POST" id="wizardForm">
        @csrf
        
        <!-- Hidden input for final status (Draft / Proses) -->
        <input type="hidden" name="status" x-model="status">

        <!-- ═════════════════ STEP 1: INFO BATCH ═════════════════ -->
        <div x-show="step === 1" class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-150 dark:border-slate-800">1. Informasi Dasar Produksi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Batch -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Nama Batch / Keterangan</label>
                        <input 
                            type="text" 
                            name="nama_batch" 
                            x-model="nama_batch" 
                            required
                            placeholder="Contoh: Produksi Susu Coklat Pagi"
                            class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                        <input 
                            type="date" 
                            name="tanggal_mulai" 
                            x-model="tanggal_mulai" 
                            required
                            class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <!-- Produk Output -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Produk Yang Dihasilkan</label>
                        <select 
                            name="produk_output_id" 
                            x-model="produk_output_id" 
                            required
                            @change="onProductChange()"
                            class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_produk }} ({{ $p->kode_produk }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Jumlah Batch -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Jumlah Batch</label>
                            <input 
                                type="number" 
                                name="jumlah_batch" 
                                x-model.number="jumlah_batch" 
                                required
                                min="1"
                                class="w-full text-sm font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <!-- Target Output -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Target Output (Unit)</label>
                            <input 
                                type="number" 
                                name="target_output" 
                                x-model.number="target_output" 
                                required
                                min="1"
                                class="w-full text-sm font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea 
                        name="catatan" 
                        x-model="catatan" 
                        rows="2"
                        placeholder="Rincian tambahan mengenai batch ini..."
                        class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                </div>
            </div>

            <!-- Real-time Live BOM Stock Checking Preview -->
            <div x-show="produk_output_id && requiredMaterials.length > 0" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-150 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Estimasi Pemakaian & Ketersediaan Stok</h3>
                    <span class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold uppercase tracking-wider">Pengecekan Otomatis</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase font-bold tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2">
                                <th class="pb-2">Bahan Baku</th>
                                <th class="pb-2">Kebutuhan per Batch</th>
                                <th class="pb-2">Total Dibutuhkan</th>
                                <th class="pb-2">Stok Tersedia Saat Ini</th>
                                <th class="pb-2 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-medium">
                            <template x-for="item in requiredMaterials" :key="item.bahan_baku_id">
                                <tr :class="item.isLow ? 'bg-red-50/50 dark:bg-red-950/20 text-red-900 dark:text-red-300' : 'text-slate-700 dark:text-slate-300'">
                                    <td class="py-3 font-semibold" x-text="item.nama_bahan"></td>
                                    <td class="py-3 font-mono" x-text="formatNumber(item.qty_per_batch) + ' ' + item.satuan"></td>
                                    <td class="py-3 font-mono font-bold" x-text="formatNumber(item.qty_needed) + ' ' + item.satuan"></td>
                                    <td class="py-3 font-mono" x-text="formatNumber(item.stok_available) + ' ' + item.satuan"></td>
                                    <td class="py-3 text-right">
                                        <template x-if="item.isLow">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-900/50">
                                                Stok Kurang!
                                            </span>
                                        </template>
                                        <template x-if="!item.isLow">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/30">
                                                Cukup
                                            </span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Wizard Footer Step 1 -->
            <div class="flex justify-end">
                <button 
                    type="button" 
                    @click="goToStep(2)"
                    :disabled="!canGoToStep2"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold rounded-lg text-white shadow-sm transition-colors disabled:opacity-40 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Lanjutkan ke Biaya
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- ═════════════════ STEP 2: RINCIAN BIAYA ═════════════════ -->
        <div x-show="step === 2" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card 1: Bahan Baku (SNAPSHOTTED FROM BOM) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">1. Biaya Bahan Baku (Snapshot BOM)</h3>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">Berdasarkan BOM × Batch</span>
                    </div>

                    <div class="space-y-3 max-h-[350px] overflow-y-auto pr-1">
                        <template x-for="item in requiredMaterials" :key="item.bahan_baku_id">
                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-slate-900 dark:text-white text-xs" x-text="item.nama_bahan"></span>
                                    <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500" x-text="'Harga rata-rata: Rp ' + formatMoney(item.harga_rata_rata)"></span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <!-- Input Qty -->
                                    <div class="flex-1">
                                        <div class="relative rounded-md shadow-sm">
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                :name="'bahan_baku_kuantitas['+item.bahan_baku_id+']'" 
                                                x-model.number="item.qty_needed"
                                                @input="recalculateBahanBakuCost(item)"
                                                required
                                                class="w-full text-xs font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white pr-10 focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                            />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 font-mono text-[10px]" x-text="item.satuan"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Input Cost -->
                                    <div class="w-36">
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 text-[10px]">Rp</div>
                                            <input 
                                                type="number" 
                                                step="1" 
                                                :name="'bahan_baku['+item.bahan_baku_id+']'" 
                                                x-model.number="item.total_cost"
                                                required
                                                class="w-full text-xs font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white pl-8 focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Card 2: Bahan Penolong (DYNAMIC) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">2. Bahan Penolong / Kemasan</h3>
                        <button 
                            type="button" 
                            @click="addBahanPenolong()"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-900/30 transition-colors"
                        >
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div class="space-y-2 max-h-[350px] overflow-y-auto pr-1">
                        <template x-if="bahan_penolong.length === 0">
                            <div class="py-12 text-center text-slate-400 dark:text-slate-500 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl text-xs">
                                Tidak ada bahan penolong tambahan.
                            </div>
                        </template>

                        <template x-for="(bp, index) in bahan_penolong" :key="index">
                            <div class="flex gap-2 items-center">
                                <input 
                                    type="text" 
                                    :name="'bahan_penolong['+index+'][keterangan]'" 
                                    x-model="bp.keterangan" 
                                    required
                                    placeholder="Contoh: Botol Plastik 1L"
                                    class="flex-1 text-xs rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                />
                                <div class="relative w-32 rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400 text-[10px]">Rp</div>
                                    <input 
                                        type="number" 
                                        :name="'bahan_penolong['+index+'][nominal]'" 
                                        x-model.number="bp.nominal" 
                                        required
                                        min="0"
                                        placeholder="0"
                                        class="w-full text-xs font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white pl-7 focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                    />
                                </div>
                                <button type="button" @click="removeBahanPenolong(index)" class="text-rose-600 hover:text-rose-700 p-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Card 3: Tenaga Kerja Langsung (FORMULA DYNAMIC) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">3. Biaya Tenaga Kerja Langsung</h3>
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 font-mono uppercase">Kalkulator Jam Kerja</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Estimasi Jam Kerja</label>
                            <input 
                                type="number" 
                                x-model.number="labor_hours" 
                                @input="calculateLaborCost()"
                                min="1"
                                class="w-full text-xs font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                            />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Tarif per Jam (Rp)</label>
                            <input 
                                type="number" 
                                x-model.number="labor_rate" 
                                @input="calculateLaborCost()"
                                min="0"
                                class="w-full text-xs font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                            />
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Total Biaya Tenaga Kerja:</span>
                        <div class="relative w-40 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 text-xs">Rp</div>
                            <input 
                                type="number" 
                                name="tenaga_kerja_nominal" 
                                x-model.number="tenaga_kerja_nominal" 
                                required
                                min="0"
                                class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 pl-8 focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                            />
                        </div>
                    </div>
                </div>

                <!-- Card 4: Biaya Overhead (DYNAMIC) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">4. Biaya Overhead Pabrik</h3>
                        <button 
                            type="button" 
                            @click="addOverhead()"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-900/30 transition-colors"
                        >
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div class="space-y-2 max-h-[350px] overflow-y-auto pr-1">
                        <template x-if="overhead.length === 0">
                            <div class="py-12 text-center text-slate-400 dark:text-slate-500 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl text-xs">
                                Tidak ada biaya overhead pabrik tambahan.
                            </div>
                        </template>

                        <template x-for="(ov, index) in overhead" :key="index">
                            <div class="flex gap-2 items-center">
                                <input 
                                    type="text" 
                                    :name="'overhead['+index+'][keterangan]'" 
                                    x-model="ov.keterangan" 
                                    required
                                    placeholder="Contoh: Listrik & Gas Produksi"
                                    class="flex-1 text-xs rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                />
                                <div class="relative w-32 rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400 text-[10px]">Rp</div>
                                    <input 
                                        type="number" 
                                        :name="'overhead['+index+'][nominal]'" 
                                        x-model.number="ov.nominal" 
                                        required
                                        min="0"
                                        placeholder="0"
                                        class="w-full text-xs font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white pl-7 focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                    />
                                </div>
                                <button type="button" @click="removeOverhead(index)" class="text-rose-600 hover:text-rose-700 p-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Sticky Estimations Bottom Bar -->
            <div class="bg-indigo-900 text-white border border-indigo-800 rounded-xl p-5 shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="grid grid-cols-2 md:flex items-center gap-6">
                    <div>
                        <span class="block text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Total Biaya Produksi</span>
                        <span class="text-lg font-bold font-mono">Rp <span x-text="formatMoney(calculateTotalBiaya())"></span></span>
                    </div>
                    <div class="border-l border-indigo-800 pl-6 hidden md:block"></div>
                    <div>
                        <span class="block text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Target Output</span>
                        <span class="text-lg font-bold font-mono"><span x-text="target_output"></span> <span class="text-xs text-indigo-300">Unit</span></span>
                    </div>
                    <div class="border-l border-indigo-800 pl-6 hidden md:block"></div>
                    <div>
                        <span class="block text-[10px] font-bold text-amber-300 uppercase tracking-wider">Estimasi HPP per Unit</span>
                        <span class="text-xl font-bold font-mono text-amber-300">Rp <span x-text="formatMoney(calculateEstimasiHpp())"></span></span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 shrink-0">
                    <button 
                        type="button" 
                        @click="goToStep(1)"
                        class="px-4 py-2 border border-indigo-700 hover:bg-indigo-850 rounded-lg text-xs font-semibold transition-colors"
                    >
                        Kembali
                    </button>
                    <button 
                        type="button" 
                        @click="goToStep(3)"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-indigo-900 rounded-lg text-xs font-semibold shadow-sm transition-colors"
                    >
                        Lanjut ke Review
                    </button>
                </div>
            </div>
        </div>

        <!-- ═════════════════ STEP 3: REVIEW & START ═════════════════ -->
        <div x-show="step === 3" class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">3. Review Formula & Kalkulasi HPP</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Silakan tinjau kembali data produksi sebelum disimpan atau dimulai.</p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-slate-800">
                    <!-- Left Column: Info Batch Summary -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Detail Batch</h4>
                        
                        <div class="grid grid-cols-2 gap-y-3 text-sm">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Nama Batch:</span>
                            <span class="text-slate-900 dark:text-white font-bold" x-text="nama_batch"></span>

                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Tanggal Mulai:</span>
                            <span class="text-slate-900 dark:text-white font-mono" x-text="tanggal_mulai"></span>

                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Produk Dihasilkan:</span>
                            <span class="text-slate-900 dark:text-white font-bold" x-text="getProductName()"></span>

                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Jumlah Batch:</span>
                            <span class="text-slate-900 dark:text-white font-mono" x-text="jumlah_batch + 'x'"></span>

                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Target Output:</span>
                            <span class="text-slate-900 dark:text-white font-mono" x-text="target_output + ' Unit'"></span>

                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Catatan:</span>
                            <span class="text-slate-900 dark:text-white italic text-xs" x-text="catatan || '-'"></span>
                        </div>
                    </div>

                    <!-- Right Column: Cost Breakdown -->
                    <div class="space-y-4 md:pl-6 pt-4 md:pt-0">
                        <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Rincian Komponen Biaya</h4>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Total Biaya Bahan Baku:</span>
                                <span class="font-mono text-slate-900 dark:text-white font-semibold">Rp <span x-text="formatMoney(calculateTotalBahanBaku())"></span></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Total Bahan Penolong:</span>
                                <span class="font-mono text-slate-900 dark:text-white font-semibold">Rp <span x-text="formatMoney(calculateTotalPenolong())"></span></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Tenaga Kerja Langsung:</span>
                                <span class="font-mono text-slate-900 dark:text-white font-semibold">Rp <span x-text="formatMoney(tenaga_kerja_nominal)"></span></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Overhead Pabrik:</span>
                                <span class="font-mono text-slate-900 dark:text-white font-semibold">Rp <span x-text="formatMoney(calculateTotalOverhead())"></span></span>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-800 my-3 pt-3 flex items-center justify-between text-base font-bold">
                                <span class="text-slate-900 dark:text-white">Total Biaya Produksi:</span>
                                <span class="font-mono text-indigo-600 dark:text-indigo-400">Rp <span x-text="formatMoney(calculateTotalBiaya())"></span></span>
                            </div>

                            <div class="p-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/30 rounded-xl flex items-center justify-between text-sm font-bold mt-4">
                                <span class="text-amber-800 dark:text-amber-300">Estimasi HPP per Unit:</span>
                                <span class="font-mono text-amber-700 dark:text-amber-400">Rp <span x-text="formatMoney(calculateEstimasiHpp())"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wizard Footer Step 3 Actions -->
            <div class="flex justify-between items-center">
                <button 
                    type="button" 
                    @click="goToStep(2)"
                    class="px-4 py-2.5 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-semibold rounded-lg text-slate-700 dark:text-slate-300 transition-colors"
                >
                    Kembali
                </button>

                <div class="flex gap-3">
                    <!-- Action 1: Save Draft -->
                    <button 
                        type="button" 
                        @click="submitForm('Draft')"
                        class="px-5 py-2.5 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-semibold rounded-lg text-slate-700 dark:text-slate-300 transition-colors"
                    >
                        Simpan Sebagai Draft
                    </button>

                    <!-- Action 2: Mulai Produksi -->
                    <button 
                        type="button" 
                        @click="submitForm('Proses')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold rounded-lg text-white shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.64 8.38a14.98 14.98 0 00-6.16 12.12A14.98 14.98 0 0015.59 14.37z" />
                        </svg>
                        Mulai Produksi (Proses)
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Data passed from controller
    const productsData = @json($produks->keyBy('id'));
    const rawMaterialsData = @json($bahanBakus->keyBy('id'));

    function productionWizard() {
        return {
            step: 1,
            status: 'Draft',

            // Form inputs
            nama_batch: '',
            tanggal_mulai: new Date().toISOString().split('T')[0],
            produk_output_id: '',
            jumlah_batch: 1,
            target_output: 100,
            catatan: '',

            // Component dynamic inputs
            requiredMaterials: [],
            bahan_penolong: [],
            overhead: [],
            labor_hours: 8,
            labor_rate: 15000,
            tenaga_kerja_nominal: 120000,

            // Computed property to allow step transition
            get canGoToStep2() {
                if (!this.nama_batch || !this.produk_output_id || this.jumlah_batch < 1 || this.target_output < 1) {
                    return false;
                }
                // Check if any ingredient is low in stock
                let lowStock = this.requiredMaterials.some(item => item.isLow);
                return !lowStock;
            },

            goToStep(targetStep) {
                if (targetStep === 2 && !this.canGoToStep2) return;
                this.step = targetStep;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            onProductChange() {
                this.requiredMaterials = [];
                if (!this.produk_output_id) return;

                const selectedProd = productsData[this.produk_output_id];
                if (selectedProd && selectedProd.bom) {
                    this.requiredMaterials = selectedProd.bom.map(bomItem => {
                        const material = rawMaterialsData[bomItem.bahan_baku_id];
                        const qtyNeeded = bomItem.kuantitas_per_batch * this.jumlah_batch;
                        const stokAvailable = material ? material.stok : 0;
                        const totalCost = qtyNeeded * (material ? parseFloat(material.harga_rata_rata) : 0);

                        return {
                            bahan_baku_id: bomItem.bahan_baku_id,
                            nama_bahan: material ? material.nama_bahan : 'Tidak Dikenal',
                            qty_per_batch: parseFloat(bomItem.kuantitas_per_batch),
                            qty_needed: qtyNeeded,
                            satuan: bomItem.satuan,
                            stok_available: parseFloat(stokAvailable),
                            harga_rata_rata: material ? parseFloat(material.harga_rata_rata) : 0,
                            total_cost: Math.round(totalCost),
                            isLow: stokAvailable < qtyNeeded
                        };
                    });
                }
            },

            // Trigger when quantity changes in Step 2
            recalculateBahanBakuCost(item) {
                item.total_cost = Math.round(item.qty_needed * item.harga_rata_rata);
            },

            // Step 2 Helper operations
            addBahanPenolong() {
                this.bahan_penolong.push({ keterangan: '', nominal: '' });
            },

            removeBahanPenolong(index) {
                this.bahan_penolong.splice(index, 1);
            },

            addOverhead() {
                this.overhead.push({ keterangan: '', nominal: '' });
            },

            removeOverhead(index) {
                this.overhead.splice(index, 1);
            },

            calculateLaborCost() {
                this.tenaga_kerja_nominal = this.labor_hours * this.labor_rate;
            },

            // Calculations
            calculateTotalBahanBaku() {
                return this.requiredMaterials.reduce((sum, item) => sum + (parseFloat(item.total_cost) || 0), 0);
            },

            calculateTotalPenolong() {
                return this.bahan_penolong.reduce((sum, item) => sum + (parseFloat(item.nominal) || 0), 0);
            },

            calculateTotalOverhead() {
                return this.overhead.reduce((sum, item) => sum + (parseFloat(item.nominal) || 0), 0);
            },

            calculateTotalBiaya() {
                return this.calculateTotalBahanBaku() + 
                       this.calculateTotalPenolong() + 
                       (parseFloat(this.tenaga_kerja_nominal) || 0) + 
                       this.calculateTotalOverhead();
            },

            calculateEstimasiHpp() {
                if (this.target_output <= 0) return 0;
                return Math.round(this.calculateTotalBiaya() / this.target_output);
            },

            getProductName() {
                if (this.produk_output_id && productsData[this.produk_output_id]) {
                    return productsData[this.produk_output_id].nama_produk;
                }
                return '-';
            },

            submitForm(statusVal) {
                this.status = statusVal;
                this.$nextTick(() => {
                    document.getElementById('wizardForm').submit();
                });
            },

            // Formatting
            formatNumber(val) {
                if (isNaN(val)) return '0';
                return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(val);
            },

            formatMoney(val) {
                if (isNaN(val)) return '0';
                return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(val);
            }
        };
    }
</script>
@endsection
