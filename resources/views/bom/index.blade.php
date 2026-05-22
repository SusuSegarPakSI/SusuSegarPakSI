@extends('layouts.app')

@section('title', 'Resep Bill of Materials (BOM)')
@section('breadcrumb', 'BOM')

@section('content')
<div class="space-y-6" x-data="bomManager()">
    <!-- Header Page -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Resep Bill of Materials</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola formula dan resep standar bahan baku untuk setiap produk jadi.</p>
        </div>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/30 rounded-xl text-sm text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4">Kode Produk</th>
                        <th class="px-6 py-4">Bahan Baku & Kuantitas per Batch</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                    @forelse($produks as $produk)
                        <tr class="hover:bg-slate-50/55 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-950 dark:text-white">
                                {{ $produk->nama_produk }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-mono">
                                {{ $produk->kode_produk }}
                            </td>
                            <td class="px-6 py-4">
                                @if($produk->bom->isEmpty())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-400 border border-amber-200/50 dark:border-amber-900/30">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        Resep Belum Ada
                                    </span>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($produk->bom as $item)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                <span class="font-semibold text-slate-900 dark:text-white">{{ $item->bahanBaku->nama_bahan }}</span>
                                                <span class="text-slate-400 dark:text-slate-500">•</span>
                                                <span class="font-mono text-indigo-600 dark:text-indigo-400">{{ number_format($item->kuantitas_per_batch, 2, ',', '.') }}</span>
                                                <span class="text-slate-500 dark:text-slate-400 font-mono">{{ $item->satuan }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @role('admin')
                                    <button 
                                        @click="openEditModal({{ json_encode($produk) }}, {{ json_encode($produk->bom) }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-900/30 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        Edit Resep
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 italic">Lihat Saja</span>
                                @endrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H3.75m0 18.75h16.5V6H3.75v15Z" />
                                </svg>
                                Belum ada data produk untuk dikonfigurasi BOM.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($produks->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                {{ $produks->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Edit Resep (Admin Only) -->
    @role('admin')
    <div 
        x-show="isModalOpen" 
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
        <div class="fixed inset-0 bg-slate-950/60 dark:bg-slate-950/80 backdrop-blur-sm" @click="closeModal()"></div>

        <!-- Modal Container -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden transition-all transform"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Edit Resep BOM</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="currentProduct.nama_produk"></p>
                    </div>
                    <button @click="closeModal()" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body (Form) -->
                <form :action="'/bom/' + currentProduct.id" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Bahan Baku Formula</span>
                            <button 
                                type="button" 
                                @click="addRow()"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Bahan
                            </button>
                        </div>

                        <!-- Ingredients List -->
                        <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                            <template x-if="rows.length === 0">
                                <div class="py-8 text-center text-slate-500 dark:text-slate-400 border border-dashed border-slate-200 dark:border-slate-800 rounded-lg">
                                    Belum ada bahan baku terpilih. Silakan klik "Tambah Bahan" untuk merumuskan resep.
                                </div>
                            </template>

                            <template x-for="(row, index) in rows" :key="index">
                                <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/30 border border-slate-200 dark:border-slate-800/80 rounded-xl">
                                    <!-- Bahan Baku Dropdown -->
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Bahan Baku</label>
                                        <select 
                                            :name="'bom['+index+'][bahan_baku_id]'" 
                                            x-model="row.bahan_baku_id"
                                            required
                                            @change="updateSatuan(row)"
                                            class="w-full text-xs font-medium rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                        >
                                            <option value="">-- Pilih Bahan --</option>
                                            @foreach($bahanBakus as $b)
                                                <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kuantitas -->
                                    <div class="w-32">
                                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Qty per Batch</label>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            :name="'bom['+index+'][kuantitas_per_batch]'" 
                                            x-model="row.kuantitas_per_batch"
                                            required
                                            min="0.01"
                                            placeholder="0.00"
                                            class="w-full text-xs font-mono rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                        />
                                    </div>

                                    <!-- Satuan (Auto) -->
                                    <div class="w-24">
                                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Satuan</label>
                                        <input 
                                            type="text" 
                                            :name="'bom['+index+'][satuan]'" 
                                            x-model="row.satuan"
                                            readonly
                                            placeholder="Unit"
                                            class="w-full text-xs font-mono rounded-lg border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 py-1.5"
                                        />
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="self-end pb-1">
                                        <button 
                                            type="button" 
                                            @click="removeRow(index)"
                                            class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors focus:outline-none"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.24 9m4.788 0L9.24 9m4.788-1.042a48.536 48.536 0 00-3.076 0M9.75 4.5h4.5m-8.901 2.017l1.17 6.892a2.25 2.25 0 002.247 2.118h7.915a2.25 2.25 0 002.247-2.118L19.5 7.13m-14.302 0a48.11 48.11 0 013.478-.397m7.5 0a48.11 48.11 0 013.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m0 0c.34-.06.68-.116 1.022-.165" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            @click="closeModal()"
                            class="px-4 py-2 text-xs font-semibold border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-700 dark:text-slate-300 transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm rounded-lg transition-colors"
                        >
                            Simpan Resep
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endrole
</div>

<script>
    // List of materials mapping ID => Satuan for dynamic display in wizard
    const materialsData = @json($bahanBakus->keyBy('id'));

    function bomManager() {
        return {
            isModalOpen: false,
            currentProduct: {},
            rows: [],

            openEditModal(product, bomItems) {
                this.currentProduct = product;
                this.rows = bomItems.map(item => ({
                    id: item.id,
                    bahan_baku_id: item.bahan_baku_id,
                    kuantitas_per_batch: item.kuantitas_per_batch,
                    satuan: item.satuan
                }));
                this.isModalOpen = true;
            },

            closeModal() {
                this.isModalOpen = false;
                this.currentProduct = {};
                this.rows = [];
            },

            addRow() {
                this.rows.push({
                    bahan_baku_id: '',
                    kuantitas_per_batch: '',
                    satuan: ''
                });
            },

            removeRow(index) {
                this.rows.splice(index, 1);
            },

            updateSatuan(row) {
                if (row.bahan_baku_id && materialsData[row.bahan_baku_id]) {
                    row.satuan = materialsData[row.bahan_baku_id].satuan;
                } else {
                    row.satuan = '';
                }
            }
        }
    }
</script>
@endsection
