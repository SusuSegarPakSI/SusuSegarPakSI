@extends('layouts.app')

@section('title', 'Adjustment Stok Persediaan')
@section('breadcrumb', 'Persediaan / Adjustment')

@section('content')
<div class="space-y-6" x-data="adjustmentForm()">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('persediaan.index') }}" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Adjustment Stok Manual</h1>
            <p class="text-sm text-slate-500 mt-0.5">Sesuaikan selisih stok sistem dengan hasil stock opname fisik di gudang.</p>
        </div>
    </div>

    <!-- Adjustment Form -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm max-w-2xl">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Form Penyesuaian Stok</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pilih item dan masukkan jumlah stok fisik terbaru.</p>
        </div>

        <form action="{{ route('persediaan.adjustment.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- 1. Tipe Item -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Tipe Persediaan <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="item_type" value="produk" x-model="itemType" @change="resetSelection()"
                               class="w-4 h-4 border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-900 cursor-pointer">
                        <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">Produk Jadi</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="item_type" value="bahan_baku" x-model="itemType" @change="resetSelection()"
                               class="w-4 h-4 border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-900 cursor-pointer">
                        <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">Bahan Baku & Penolong</span>
                    </label>
                </div>
            </div>

            <!-- 2. Searchable Item Selector -->
            <div class="space-y-1.5" x-data="{ open: false, search: '' }">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Pilih Item Persediaan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <button type="button" @click="open = !open"
                            class="block w-full text-left px-3 py-2 pr-10 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                        <span x-text="selectedItemName ? selectedItemName : 'Pilih item...'">Pilih item...</span>
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                        </span>
                    </button>
                    <input type="hidden" name="item_id" :value="selectedItemId" required>

                    <!-- Dropdown Panel -->
                    <div x-show="open" @click.away="open = false"
                         class="absolute left-0 mt-1 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg z-50 overflow-hidden">
                        <div class="p-2 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                            <input type="text" x-model="search" placeholder="Cari item..."
                                   class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <ul class="max-h-52 overflow-y-auto p-1.5 space-y-0.5">
                            <template x-for="item in filterItems(search)" :key="item.id">
                                <li>
                                    <button type="button" @click="selectItem(item.id, item.name, item.stok, item.satuan); open = false"
                                            class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-slate-700 dark:text-slate-200 flex items-center justify-between">
                                        <div>
                                            <span class="font-medium" x-text="item.name"></span>
                                            <span class="text-[10px] text-slate-400 ml-1.5" x-text="`(${item.kode})`"></span>
                                        </div>
                                        <span class="font-mono text-slate-500" x-text="`${item.stok} ${item.satuan}`"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Stok Sistem & Stok Fisik Row -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Stok Sistem (Live Load) -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-350">Stok Tercatat (Sistem)</label>
                    <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg p-2.5 text-sm font-mono text-slate-500 flex justify-between">
                        <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="stokSistem !== null ? stokSistem : '-'">-</span>
                        <span class="text-xs text-slate-400" x-text="satuan"></span>
                    </div>
                </div>

                <!-- Stok Fisik (Input) -->
                <div class="space-y-1.5">
                    <label for="stok_fisik" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Stok Fisik Aktual <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="stok_fisik" name="stok_fisik" required min="0" x-model.number="stokFisik"
                               class="block w-full px-3 py-2 text-sm font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                        <span class="absolute inset-y-0 right-3 flex items-center text-xs text-slate-450 pointer-events-none" x-text="satuan"></span>
                    </div>
                </div>
            </div>

            <!-- Live Selisih Calculator -->
            <div x-show="selectedItemId !== ''" x-transition
                 class="p-4 bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 rounded-xl flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 font-mono">
                <span>KALKULASI SELISIH:</span>
                <div class="text-right">
                    <span class="text-slate-450">Fisik (<span x-text="stokFisik"></span>) - Sistem (<span x-text="stokSistem"></span>) =</span>
                    <span class="font-bold text-sm ml-1"
                          :class="calculateSelisih() > 0 ? 'text-emerald-600 dark:text-emerald-400' : (calculateSelisih() < 0 ? 'text-red-600 dark:text-red-400' : 'text-slate-650')">
                        <span x-text="calculateSelisih() > 0 ? '+' : ''"></span><span x-text="calculateSelisih()"></span>
                        <span class="text-xs font-sans font-normal ml-0.5" x-text="satuan"></span>
                    </span>
                </div>
            </div>

            <!-- 3. Alasan Adjustment -->
            <div class="space-y-1.5">
                <label for="alasan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Alasan Penyesuaian <span class="text-red-500">*</span>
                </label>
                <textarea id="alasan" name="alasan" rows="3" required placeholder="Tulis alasan penyesuaian (contoh: Barang Pecah, Susut Penguapan, Stock Opname bulanan)..."
                          class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"></textarea>
            </div>

            <!-- 4. Tanggal Adjustment -->
            <div class="space-y-1.5">
                <label for="tanggal" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Tanggal Adjustment <span class="text-red-500">*</span>
                </label>
                <input type="date" id="tanggal" name="tanggal" required value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-5 mt-2">
                <a href="{{ route('persediaan.index') }}"
                   class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" :disabled="selectedItemId === ''"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                    Simpan Adjustment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function adjustmentForm() {
        return {
            itemType: '{{ request()->get("type", "produk") }}',
            selectedItemId: '{{ request()->get("item_id", "") }}',
            selectedItemName: '',
            stokSistem: null,
            stokFisik: 0,
            satuan: '',

            produks: [
                @foreach ($produks as $p)
                    { id: '{{ $p->id }}', name: '{{ $p->nama_produk }}', kode: '{{ $p->kode_produk }}', stok: {{ $p->stok }}, satuan: '{{ $p->satuan }}' },
                @endforeach
            ],

            bahanBakus: [
                @foreach ($bahanBakus as $b)
                    { id: '{{ $b->id }}', name: '{{ $b->nama_bahan }}', kode: '{{ $b->kode_bahan }}', stok: {{ $b->stok }}, satuan: '{{ $b->satuan }}' },
                @endforeach
            ],

            init() {
                if (this.selectedItemId !== '') {
                    // pre-load selected item if given in query string
                    let arr = this.itemType === 'produk' ? this.produks : this.bahanBakus;
                    let match = arr.find(x => x.id === this.selectedItemId);
                    if (match) {
                        this.selectItem(match.id, match.name, match.stok, match.satuan);
                    }
                }
            },

            resetSelection() {
                this.selectedItemId = '';
                this.selectedItemName = '';
                this.stokSistem = null;
                this.stokFisik = 0;
                this.satuan = '';
            },

            filterItems(search) {
                let arr = this.itemType === 'produk' ? this.produks : this.bahanBakus;
                if (!search) return arr;
                return arr.filter(x => 
                    x.name.toLowerCase().includes(search.toLowerCase()) || 
                    x.kode.toLowerCase().includes(search.toLowerCase())
                );
            },

            selectItem(id, name, stok, satuan) {
                this.selectedItemId = id;
                this.selectedItemName = name;
                this.stokSistem = stok;
                this.stokFisik = stok;
                this.satuan = satuan;
            },

            calculateSelisih() {
                if (this.stokSistem === null) return 0;
                return this.stokFisik - this.stokSistem;
            }
        };
    }
</script>
@endsection
