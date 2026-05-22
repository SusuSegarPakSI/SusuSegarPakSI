@extends('layouts.app')

@section('content')
@role('admin')
<div x-data="posApp()" class="flex flex-col lg:flex-row gap-6 min-h-[calc(100vh-120px)] -m-6 p-6 bg-slate-50 dark:bg-slate-950 transition-colors">
    
    <!-- LEFT SIDE: Product Explorer (60% width) -->
    <div class="w-full lg:w-[62%] flex flex-col gap-6">
        
        <!-- Premium Catalogue Header & Toolbar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-100 dark:border-slate-800/60 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">Katalog Kasir POS</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Pilih produk berkualitas di bawah untuk dimasukkan dalam keranjang belanja.</p>
                </div>
                
                <!-- Date input in styled container -->
                <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-xl self-start md:self-auto shrink-0 shadow-inner">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <input type="date" x-model="tanggal" class="bg-transparent border-none text-xs font-semibold text-slate-700 dark:text-slate-300 p-0 focus:ring-0 focus:outline-none w-28">
                </div>
            </div>

            <!-- Search and Searchable Select Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Search bar -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama atau kode produk..." class="block w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-50 placeholder-slate-400 dark:placeholder-slate-600 rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Premium Customer Search Dropdown -->
                <div class="relative" x-data="{ open: false, filterText: '' }">
                    <div @click="open = !open" class="flex items-center justify-between w-full px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl shadow-inner cursor-pointer select-none group hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 font-semibold" x-text="selectedCustomerName"></span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>

                    <!-- Dropdown List -->
                    <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;" class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-h-60 overflow-y-auto p-2.5 space-y-1.5">
                        <div class="relative mb-1">
                            <input type="text" x-model="filterText" placeholder="Cari nama pelanggan..." class="block w-full pl-7 pr-3 py-1.5 text-xs border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <svg class="w-3 h-3 absolute left-2.5 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        
                        <!-- General customer option -->
                        <div @click="selectCustomer('', 'Pelanggan Umum'); open = false" class="px-3.5 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-indigo-600 hover:text-white rounded-xl cursor-pointer transition-colors font-semibold flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Pelanggan Umum
                        </div>

                        <!-- Loop customers -->
                        <template x-for="c in customers.filter(c => c.nama.toLowerCase().includes(filterText.toLowerCase()))" :key="c.id">
                            <div @click="selectCustomer(c.id, c.nama); open = false" class="px-3.5 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-indigo-600 hover:text-white rounded-xl cursor-pointer transition-colors flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                <span x-text="c.nama"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Category Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-thin">
                <button type="button" @click="activeCategory = 'Semua'" 
                        :class="activeCategory === 'Semua' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                        class="px-4 py-1.5 text-xs rounded-full transition-all shrink-0">
                    Semua Produk
                </button>
                <template x-for="cat in getCategories()" :key="cat">
                    <button type="button" @click="activeCategory = cat" 
                            :class="activeCategory === cat ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                            class="px-4 py-1.5 text-xs rounded-full transition-all shrink-0 capitalize"
                            x-text="cat">
                    </button>
                </template>
            </div>
        </div>

        <!-- Catalogue Cards Grid -->
        <div class="flex-1 overflow-y-auto max-h-[calc(100vh-320px)] pr-1">
            
            <!-- Empty state search -->
            <template x-if="searchQuery.trim().length > 0 && searchQuery.trim().length < 2">
                <div class="p-12 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400">
                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-300 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <p class="text-xs font-semibold">Ketik minimal 2 karakter untuk memulai pencarian produk.</p>
                </div>
            </template>

            <!-- Cards listing -->
            <template x-if="searchQuery.trim().length === 0 || searchQuery.trim().length >= 2">
                <div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        <template x-for="p in filteredProducts()" :key="p.id">
                            <div @click="p.stok > 0 ? addToCart(p) : null" 
                                 :class="p.stok > 0 
                                    ? (p.stok <= p.stok_minimum 
                                        ? 'bg-white dark:bg-slate-900 border-amber-300 dark:border-amber-900/60 hover:border-amber-500/80 cursor-pointer shadow-sm hover:shadow-md hover:-translate-y-0.5' 
                                        : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-indigo-500/60 cursor-pointer shadow-sm hover:shadow-md hover:-translate-y-0.5')
                                    : 'bg-slate-100/60 dark:bg-slate-950/20 border-slate-200 dark:border-slate-900 opacity-50 cursor-not-allowed'"
                                 class="border rounded-2xl p-4.5 flex flex-col justify-between h-40 transition-all duration-200 relative overflow-hidden group select-none">
                                
                                <!-- Soft aura gradient on hover -->
                                <div x-show="p.stok > 0" class="absolute -inset-y-0 -inset-x-0 bg-gradient-to-tr from-indigo-500/0 via-indigo-500/5 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                <div class="space-y-1.5 z-10">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-mono text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-lg" x-text="p.kode_produk"></span>
                                        <template x-if="p.kategori">
                                            <span class="text-[9px] font-semibold text-indigo-500 dark:text-indigo-400 tracking-wider uppercase" x-text="p.kategori"></span>
                                        </template>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2" x-text="p.nama_produk"></h3>
                                </div>

                                <div class="flex items-end justify-between mt-4 z-10 pt-2 border-t border-slate-100 dark:border-slate-800/40">
                                    <span class="text-sm font-mono font-bold text-slate-900 dark:text-slate-50" x-text="formatRupiah(p.harga_jual)"></span>
                                    
                                    <div class="text-right">
                                        <template x-if="p.stok > 0">
                                            <span :class="p.stok > p.stok_minimum ? 'text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800' : 'text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-900/40'" class="text-[10px] font-bold px-2 py-0.5 rounded-full border">
                                                Stok: <span class="font-mono font-bold" x-text="p.stok"></span>
                                            </span>
                                        </template>
                                        <template x-if="p.stok === 0">
                                            <span class="text-[9px] text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/40 font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Habis</span>
                                        </template>
                                    </div>
                                </div>

                                <!-- Absolute Floating Plus Overlay -->
                                <div x-show="p.stok > 0" class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                    <div class="h-6 w-6 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow shadow-indigo-500/40">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- RIGHT SIDE: Cart Sidebar (40% width) -->
    <div class="w-full lg:w-[38%] flex flex-col bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-md overflow-hidden min-h-[500px]">
        
        <!-- Cart Header -->
        <div class="px-5 py-4.5 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-50">Keranjang Kasir</h3>
            </div>
            <span class="font-mono text-xs bg-indigo-100 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400 px-3 py-1 rounded-full font-bold" x-text="cart.reduce((sum, item) => sum + item.kuantitas, 0) + ' Item'"></span>
        </div>

        <!-- Cart Items List (Scrollable) -->
        <div class="flex-1 overflow-y-auto px-5 py-4 divide-y divide-slate-100 dark:divide-slate-800/80">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 py-16 text-center">
                    <svg class="w-12 h-12 mb-3 stroke-[1.2] text-slate-300 dark:text-slate-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Keranjang Belanja Masih Kosong</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 max-w-[200px] mx-auto">Ketuk kartu katalog produk di bagian kiri untuk memasukkan ke struk kasir.</p>
                </div>
            </template>

            <template x-for="item in cart" :key="item.produk_id">
                <div class="py-3.5 flex items-center justify-between gap-3 group">
                    <div class="min-w-0 flex-1">
                        <h4 class="text-xs font-bold text-slate-900 dark:text-slate-50 truncate" x-text="item.nama_produk"></h4>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="text-[10px] font-mono font-medium text-slate-400" x-text="formatRupiah(item.harga_jual)"></span>
                            <span class="text-[10px] text-slate-300 dark:text-slate-700">&bull;</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide" x-text="item.satuan"></span>
                        </div>
                    </div>

                    <!-- Quantity Changer with precise buttons -->
                    <div class="flex items-center gap-0.5 shrink-0 bg-slate-50 dark:bg-slate-950 p-0.5 rounded-lg border border-slate-200 dark:border-slate-800">
                        <button type="button" @click="updateQty(item.produk_id, item.kuantitas - 1)" class="w-6 h-6 rounded-md bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors select-none font-bold text-xs shadow-sm">-</button>
                        <input type="number" :value="item.kuantitas" @input="updateQty(item.produk_id, parseInt($event.target.value))" class="w-9 h-6 border-none bg-transparent text-slate-900 dark:text-white rounded text-center text-xs font-mono font-bold p-0 focus:outline-none focus:ring-0">
                        <button type="button" @click="updateQty(item.produk_id, item.kuantitas + 1)" class="w-6 h-6 rounded-md bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors select-none font-bold text-xs shadow-sm">+</button>
                    </div>

                    <!-- Item Subtotal -->
                    <div class="text-right shrink-0 w-22">
                        <span class="font-mono text-xs font-bold text-slate-900 dark:text-slate-50" x-text="formatRupiah(item.kuantitas * item.harga_jual)"></span>
                    </div>

                    <!-- Remove row -->
                    <button type="button" @click="removeFromCart(item.produk_id)" class="text-slate-300 hover:text-red-500 dark:text-slate-700 dark:hover:text-red-400 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-950/20 shrink-0 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Summary & Checkout Operations -->
        <div class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 p-5 space-y-4 shrink-0">
            
            <!-- Detailed calculations -->
            <div class="space-y-2 text-xs">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="font-medium">Subtotal Belanja</span>
                    <span class="font-mono font-semibold" x-text="formatRupiah(subtotal)"></span>
                </div>
                
                <div class="flex items-center justify-between gap-3 text-slate-500 dark:text-slate-400">
                    <span class="font-medium">Potongan Diskon (Rp)</span>
                    <div class="w-32 relative">
                        <input type="number" x-model.number="diskon" min="0" :max="subtotal" placeholder="0" class="block w-full text-right py-1 px-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white font-mono rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Grand Total inside visually heavy banner -->
                <div class="flex items-center justify-between p-3.5 bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-100/50 dark:border-indigo-900/30 rounded-xl mt-3 text-slate-900 dark:text-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">Grand Total</span>
                    <span class="font-mono font-bold text-indigo-700 dark:text-indigo-300 text-xl" x-text="formatRupiah(grandTotal)"></span>
                </div>
            </div>

            <!-- Payment Methods Grid with Icons -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3.5">
                <div class="grid grid-cols-3 gap-2">
                    <!-- Tunai -->
                    <label class="flex flex-col items-center justify-center p-2.5 border rounded-xl cursor-pointer transition-all select-none gap-1"
                           :class="metodeBayar === 'Tunai' 
                                ? 'bg-indigo-600 border-indigo-600 text-white font-bold shadow shadow-indigo-500/20' 
                                : 'bg-white dark:bg-slate-950 border-slate-200 dark:border-slate-850 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900'">
                        <input type="radio" name="metode_bayar" value="Tunai" x-model="metodeBayar" class="sr-only">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                        <span class="text-[10px] uppercase tracking-wide">Tunai</span>
                    </label>
                    
                    <!-- Transfer -->
                    <label class="flex flex-col items-center justify-center p-2.5 border rounded-xl cursor-pointer transition-all select-none gap-1"
                           :class="metodeBayar === 'Transfer' 
                                ? 'bg-indigo-600 border-indigo-600 text-white font-bold shadow shadow-indigo-500/20' 
                                : 'bg-white dark:bg-slate-950 border-slate-200 dark:border-slate-850 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900'">
                        <input type="radio" name="metode_bayar" value="Transfer" x-model="metodeBayar" class="sr-only">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                        <span class="text-[10px] uppercase tracking-wide">Transfer</span>
                    </label>

                    <!-- QRIS -->
                    <label class="flex flex-col items-center justify-center p-2.5 border rounded-xl cursor-pointer transition-all select-none gap-1"
                           :class="metodeBayar === 'QRIS' 
                                ? 'bg-indigo-600 border-indigo-600 text-white font-bold shadow shadow-indigo-500/20' 
                                : 'bg-white dark:bg-slate-950 border-slate-200 dark:border-slate-850 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900'">
                        <input type="radio" name="metode_bayar" value="QRIS" x-model="metodeBayar" class="sr-only">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125v-2.25ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 13.5 7.125v-2.25ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125v-2.25ZM13.5 16.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM19.5 13.5h.008v.008h-.008V13.5Zm-3 3h.008v.008h-.008v-.008Zm3 3h.008v.008h-.008v-.008Zm-3 0h.008v.008h-.008v-.008ZM16.5 13.5h.008v.008h-.008V13.5Zm3 3h.008v.008h-.008v-.008Z" />
                        </svg>
                        <span class="text-[10px] uppercase tracking-wide">QRIS</span>
                    </label>
                </div>

                <!-- Cash input form inside styled subcard -->
                <div x-show="metodeBayar === 'Tunai'" style="display: none;" 
                     class="grid grid-cols-2 gap-4 p-4 bg-emerald-50/40 dark:bg-emerald-950/10 rounded-xl border border-emerald-100/50 dark:border-emerald-950/20">
                    <div class="space-y-1">
                        <label class="block text-[9px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Jumlah Pembayaran</label>
                        <input type="number" x-model.number="jumlahBayar" min="0" 
                               class="block w-full py-1.5 px-3 border border-emerald-200/50 dark:border-emerald-900/50 bg-white dark:bg-slate-950 text-slate-900 dark:text-white font-mono font-bold text-xs rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[9px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Uang Kembalian</label>
                        <div class="w-full py-1.5 px-1.5 text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm" x-text="formatRupiah(kembalian)"></div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catatan Nota (Opsional)</label>
                    <textarea x-model="catatan" rows="2" placeholder="Tulis catatan belanja khusus di sini..." 
                              class="block w-full px-3 py-2 text-xs bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            </div>

            <!-- Submit buttons area -->
            <div class="flex flex-col gap-2 pt-3">
                <form id="checkout-form" method="POST" action="{{ route('penjualan.store') }}" @submit="submitCheckout($event)">
                    @csrf
                    <!-- Hidden elements -->
                    <input type="hidden" name="tanggal" :value="tanggal">
                    <input type="hidden" name="customer_id" :value="selectedCustomer">
                    <input type="hidden" name="diskon_nominal" :value="diskon">
                    <input type="hidden" name="metode_bayar" :value="metodeBayar">
                    <input type="hidden" name="jumlah_bayar" :value="metodeBayar === 'Tunai' ? jumlahBayar : grandTotal">
                    <input type="hidden" name="catatan" :value="catatan">

                    <template x-for="(item, index) in cart" :key="item.produk_id">
                        <div>
                            <input type="hidden" :name="'items['+index+'][produk_id]'" :value="item.produk_id">
                            <input type="hidden" :name="'items['+index+'][kuantitas]'" :value="item.kuantitas">
                        </div>
                    </template>

                    <!-- Beautiful Alert Error Warnings -->
                    <div x-show="errorMessage" style="display: none;" 
                         class="p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-xl text-red-700 dark:text-red-400 text-xs mb-3.5 font-semibold flex items-start gap-2">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <!-- Checkout button -->
                    <button type="submit" 
                            :disabled="cart.length === 0 || isSubmitting || (metodeBayar === 'Tunai' && jumlahBayar < grandTotal)"
                            class="w-full flex items-center justify-center gap-2.5 px-4 py-3 text-sm font-bold bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-indigo-600 text-white rounded-xl shadow-md shadow-indigo-500/20 transition-all focus:outline-none">
                        <template x-if="isSubmitting">
                            <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </template>
                        <span x-text="isSubmitting ? 'Memproses Transaksi...' : 'Proses Transaksi Belanja'"></span>
                    </button>
                </form>

                <!-- Reset button -->
                <button type="button" @click="resetCart()" :disabled="cart.length === 0 || isSubmitting"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 disabled:opacity-40 disabled:cursor-not-allowed rounded-xl transition-all focus:outline-none">
                    Batal / Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function posApp() {
        return {
            products: @json($products),
            customers: @json($customers),
            searchQuery: '',
            activeCategory: 'Semua',
            tanggal: new Date().toISOString().substring(0, 10),
            selectedCustomer: '',
            selectedCustomerName: 'Pelanggan Umum',
            cart: [],
            diskon: 0,
            metodeBayar: 'Tunai',
            jumlahBayar: 0,
            catatan: '',
            isSubmitting: false,
            errorMessage: '',

            getCategories() {
                // Dynamically compile categories present in loaded products
                let rawCats = this.products.map(p => p.kategori).filter(Boolean);
                return [...new Set(rawCats)];
            },

            selectCustomer(id, nama) {
                this.selectedCustomer = id;
                this.selectedCustomerName = nama;
            },

            filteredProducts() {
                let list = this.products;

                // Category filter
                if (this.activeCategory !== 'Semua') {
                    list = list.filter(p => p.kategori === this.activeCategory);
                }

                // Query search filter
                if (this.searchQuery.trim().length >= 2) {
                    let query = this.searchQuery.toLowerCase();
                    list = list.filter(p => 
                        p.nama_produk.toLowerCase().includes(query) || 
                        p.kode_produk.toLowerCase().includes(query)
                    );
                }
                return list;
            },

            addToCart(product) {
                let existing = this.cart.find(item => item.produk_id === product.id);
                if (existing) {
                    if (existing.kuantitas < product.stok) {
                        existing.kuantitas++;
                        this.errorMessage = '';
                    } else {
                        this.errorMessage = 'Gagal: Kuantitas belanja melebihi stok yang tersedia (' + product.stok + ' Pcs)!';
                    }
                } else {
                    this.cart.push({
                        produk_id: product.id,
                        nama_produk: product.nama_produk,
                        harga_jual: parseFloat(product.harga_jual),
                        stok: product.stok,
                        satuan: product.satuan,
                        kuantitas: 1
                    });
                    this.errorMessage = '';
                }
            },

            removeFromCart(productId) {
                this.cart = this.cart.filter(item => item.produk_id !== productId);
                this.errorMessage = '';
            },

            updateQty(productId, qty) {
                let item = this.cart.find(item => item.produk_id === productId);
                if (!item) return;

                qty = isNaN(qty) ? 1 : qty;
                
                if (qty <= 0) {
                    this.removeFromCart(productId);
                } else if (qty > item.stok) {
                    item.kuantitas = item.stok;
                    this.errorMessage = 'Jumlah disesuaikan ke kuantitas stok maksimal (' + item.stok + ' Pcs)';
                } else {
                    item.kuantitas = qty;
                    this.errorMessage = '';
                }
            },

            get subtotal() {
                return this.cart.reduce((sum, item) => sum + (item.kuantitas * item.harga_jual), 0);
            },

            get grandTotal() {
                if (isNaN(this.diskon) || this.diskon < 0) {
                    this.diskon = 0;
                }
                if (this.diskon > this.subtotal) {
                    this.diskon = this.subtotal;
                }
                let total = Math.max(0, this.subtotal - this.diskon);
                
                // Adjust cash payments automatically when total changes
                if (this.jumlahBayar < total && this.metodeBayar === 'Tunai') {
                    // Do not raise block immediately, let user type
                }
                return total;
            },

            get kembalian() {
                if (this.metodeBayar !== 'Tunai') return 0;
                if (isNaN(this.jumlahBayar) || this.jumlahBayar < 0) {
                    return 0;
                }
                return Math.max(0, this.jumlahBayar - this.grandTotal);
            },

            resetCart() {
                if (confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja kasir?')) {
                    this.cart = [];
                    this.diskon = 0;
                    this.jumlahBayar = 0;
                    this.catatan = '';
                    this.errorMessage = '';
                    this.selectedCustomer = '';
                    this.selectedCustomerName = 'Pelanggan Umum';
                }
            },

            submitCheckout(e) {
                if (this.cart.length === 0) {
                    e.preventDefault();
                    return;
                }
                if (this.metodeBayar === 'Tunai' && this.jumlahBayar < this.grandTotal) {
                    e.preventDefault();
                    this.errorMessage = 'Gagal: Uang pembayaran tunai kurang dari total tagihan belanja!';
                    return;
                }
                this.isSubmitting = true;
                this.errorMessage = '';
            },

            formatRupiah(val) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(val));
            }
        };
    }
</script>
@else
<div class="p-10 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 max-w-lg mx-auto my-16 shadow-lg">
    <div class="h-14 w-14 rounded-full bg-red-50 dark:bg-red-950/30 flex items-center justify-center text-red-500 mx-auto mb-5 border border-red-100 dark:border-red-900/50">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    </div>
    <h2 class="text-lg font-bold text-slate-850 dark:text-white">Akses Halaman Ditolak</h2>
    <p class="text-xs text-slate-400 dark:text-slate-500 mt-2.5 max-w-sm mx-auto leading-relaxed">Antarmuka kasir POS terintegrasi ini dilindungi oleh hak akses khusus dan hanya dapat digunakan oleh staf **Admin (Operator Harian)**.</p>
    <a href="{{ route('penjualan.index') }}" class="inline-flex items-center gap-1.5 mt-6 px-4 py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl hover:bg-indigo-100/80 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Riwayat
    </a>
</div>
@endrole
@endsection
