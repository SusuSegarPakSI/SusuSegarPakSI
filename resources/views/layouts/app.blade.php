<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - ERP Susu Segar Pak Si</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AlpineJS via CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Init theme from localStorage
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Init sidebar collapsed state
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                document.getElementById('app-shell').classList.add('sidebar-collapsed');
            }
        });
    </script>

    <style>
        /* Sidebar collapse overrides */
        #app-shell.sidebar-collapsed #sidebar {
            width: 64px; /* w-16 */
        }
        #app-shell.sidebar-collapsed .sidebar-label {
            display: none;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 font-sans antialiased text-slate-800 dark:text-slate-200 h-full overflow-hidden">

    <!-- Wrapper utama -->
    <div class="flex h-screen overflow-hidden" id="app-shell">

        <!-- ═══════════════════════════════════════════ -->
        <!-- SIDEBAR                                     -->
        <!-- ═══════════════════════════════════════════ -->
        <aside id="sidebar"
               class="flex flex-col w-60 shrink-0 border-r border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 transition-all duration-200 ease-in-out lg:relative lg:translate-x-0 fixed inset-y-0 left-0 z-50 -translate-x-full lg:translate-x-0"
               aria-label="Sidebar navigasi">

            <!-- Logo / Brand -->
            <div class="flex items-center justify-between h-14 px-4 border-b border-slate-200 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white sidebar-label truncate">
                        Susu Segar Pak Si
                    </span>
                </div>

                <!-- Collapse button for desktop -->
                <button onclick="toggleSidebarCollapse()" class="hidden lg:block p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 sidebar-label">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-0.5" x-data="{ openMaster: {{ request()->is('master*') ? 'true' : 'false' }} }">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="sidebar-label truncate">Dashboard</span>
                </a>

                {{-- Separator --}}
                <div class="pt-4 pb-1 px-3 sidebar-label">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-600">Operasional</p>
                </div>

                {{-- Master Data Collapsible --}}
                <div>
                    <button @click="openMaster = !openMaster"
                            class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75" />
                        </svg>
                        <span class="sidebar-label flex-1 text-left truncate">Master Data</span>
                        @role('manajer')
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Lihat Saja">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            </svg>
                        @endrole
                        <svg class="w-4 h-4 shrink-0 sidebar-label transition-transform" :class="openMaster ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <div x-show="openMaster" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-0.5 ml-4 pl-4 border-l border-slate-200 dark:border-slate-800 space-y-0.5 sidebar-label">
                        <a href="{{ route('master.index', ['tab' => 'produk']) }}" class="{{ request()->routeIs('master.produk.*') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100' }} flex items-center py-1.5 text-sm transition-colors">Produk</a>
                        <a href="{{ route('master.index', ['tab' => 'bahan']) }}" class="{{ request()->routeIs('master.bahan-baku.*') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100' }} flex items-center py-1.5 text-sm transition-colors">Bahan Baku</a>
                        <a href="{{ route('master.index', ['tab' => 'pelanggan']) }}" class="{{ request()->routeIs('master.pelanggan.*') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100' }} flex items-center py-1.5 text-sm transition-colors">Pelanggan</a>
                        <a href="{{ route('master.index', ['tab' => 'supplier']) }}" class="{{ request()->routeIs('master.supplier.*') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100' }} flex items-center py-1.5 text-sm transition-colors">Supplier</a>
                    </div>
                </div>

                {{-- Penjualan --}}
                <a href="{{ route('penjualan.index') }}"
                   class="{{ request()->routeIs('penjualan.index') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <span class="sidebar-label truncate">Penjualan</span>
                    </div>
                    @role('manajer')
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 sidebar-label" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Lihat Saja">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        </svg>
                    @endrole
                </a>

                {{-- Produksi --}}
                <a href="{{ route('produksi.index') }}"
                   class="{{ request()->routeIs('produksi.*') && !request()->routeIs('bom.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v1.244c0 .89-.56 1.6-1.396 1.845a9.077 9.077 0 0 1-3.69 0C3.83 5.95 3.27 5.239 3.27 4.348V3.104c0-.768.4-1.468 1.055-1.859a11.95 11.95 0 0 1 10.66 0c.655.39 1.055 1.09 1.055 1.859Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 10.5h19.5M2.25 15h19.5m-19.5 4.5h19.5" />
                        </svg>
                        <span class="sidebar-label truncate">Produksi</span>
                    </div>
                    @role('manajer')
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 sidebar-label" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Lihat Saja">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        </svg>
                    @endrole
                </a>

                {{-- Resep BOM --}}
                <a href="{{ route('bom.index') }}"
                   class="{{ request()->routeIs('bom.index') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H3.75m0 18.75h16.5V6H3.75v15Z" />
                        </svg>
                        <span class="sidebar-label truncate">Resep BOM</span>
                    </div>
                    @role('manajer')
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 sidebar-label" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Lihat Saja">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        </svg>
                    @endrole
                </a>

                {{-- Pembelian --}}
                <a href="{{ route('pembelian.index') }}"
                   class="{{ request()->routeIs('pembelian.index') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m2.25 0h1.5a1.125 1.125 0 0 0 1.125-1.125V11.25m-19.5 0h19.5L16.666 4.675A2.25 2.25 0 0 0 14.772 3.75H7.228a2.25 2.25 0 0 0-1.894.925L2.25 11.25Z" />
                        </svg>
                        <span class="sidebar-label truncate">Pembelian</span>
                    </div>
                    @role('manajer')
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 sidebar-label" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Lihat Saja">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        </svg>
                    @endrole
                </a>

                {{-- Persediaan --}}
                <a href="{{ route('persediaan.index') }}"
                   class="{{ request()->routeIs('persediaan.index') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <span class="sidebar-label truncate">Persediaan</span>
                    </div>
                    @role('manajer')
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 sidebar-label" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Lihat Saja">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        </svg>
                    @endrole
                </a>

                {{-- SDM & Penggajian --}}
                <a href="{{ route('sdm.index') }}"
                   class="{{ request()->routeIs('sdm.index') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a.75.75 0 0 0 0-1.5H6a.75.75 0 0 0 0 1.5h12Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 8.25a6 6 0 0 0-12 0v.75h12v-.75Z" />
                        </svg>
                        <span class="sidebar-label truncate">SDM & Penggajian</span>
                    </div>
                    @role('manajer')
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 sidebar-label" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Lihat Saja">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        </svg>
                    @endrole
                </a>

                {{-- Laporan Keuangan --}}
                <a href="{{ route('laporan.index') }}"
                   class="{{ request()->routeIs('laporan.index') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }} flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v5.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 18.375v-5.25ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125v-9.75ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v14.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    <span class="sidebar-label truncate">Laporan Keuangan</span>
                </a>

                {{-- Manajer Only: Pengaturan / Users --}}
                @role('manajer')
                    {{-- Separator --}}
                    <div class="pt-4 pb-1 px-3 sidebar-label">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-600">Sistem</p>
                    </div>

                    {{-- Pengaturan (collapsible) --}}
                    <div x-data="{ openSettings: {{ request()->is('pengaturan*') ? 'true' : 'false' }} }">
                        <button @click="openSettings = !openSettings"
                                class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.645-.869L9.594 3.94Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="sidebar-label flex-1 text-left truncate">Pengaturan</span>
                            <svg class="w-4 h-4 shrink-0 sidebar-label transition-transform" :class="openSettings ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>

                        <div x-show="openSettings" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-0.5 ml-4 pl-4 border-l border-slate-200 dark:border-slate-800 space-y-0.5 sidebar-label">
                            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100' }} flex items-center py-1.5 text-sm transition-colors">Manajemen User</a>
                            <a href="{{ route('pengaturan.index') }}" class="{{ request()->routeIs('pengaturan.index') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100' }} flex items-center py-1.5 text-sm transition-colors">Pengaturan Sistem</a>
                        </div>
                    </div>
                @endrole
            </nav>

            <!-- User profile (bottom) -->
            <div class="shrink-0 border-t border-slate-200 dark:border-slate-800 p-3" x-data="{ openProfile: false }">
                <div class="relative">
                    <button @click="openProfile = !openProfile"
                            class="w-full flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center shrink-0">
                            <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="text-left min-w-0 sidebar-label flex-1">
                            <p class="text-xs font-medium text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 sidebar-label shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="openProfile"
                         @click.away="openProfile = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute bottom-12 left-0 w-full mt-1.5 z-55 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-1.5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center gap-2.5 px-2.5 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    Keluar Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Backdrop (mobile) -->
        <div id="sidebar-backdrop"
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden"
             onclick="closeSidebar()"></div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- MAIN AREA                                   -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            <!-- TOP BAR -->
            <header class="flex items-center justify-between h-14 px-4 sm:px-6 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0 z-30 transition-colors">

                <!-- Kiri: Hamburger + Breadcrumb -->
                <div class="flex items-center gap-3">
                    <!-- Hamburger (mobile) -->
                    <button onclick="toggleSidebar()"
                            class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                            aria-label="Toggle sidebar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                    </button>

                    <!-- Breadcrumb -->
                    <nav aria-label="Breadcrumb">
                        <ol class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <li><a href="{{ route('dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Susu Segar Pak Si</a></li>
                            <li>
                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </li>
                            <li class="text-slate-800 dark:text-slate-200 font-medium">@yield('breadcrumb')</li>
                        </ol>
                    </nav>
                </div>

                <!-- Kanan: Actions -->
                <div class="flex items-center gap-2">
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()"
                            class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                            aria-label="Toggle dark mode">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                        </svg>
                        <svg class="w-5 h-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                        </svg>
                    </button>

                    <!-- User Name Label (Convenience) -->
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 hidden sm:inline-block px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg">
                        {{ auth()->user()->name }} ({{ auth()->user()->role }})
                    </span>
                </div>
            </header>

            <!-- CONTENT AREA -->
            <main class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Session Alerts (Toast equivalents if desired or standard inline cards) -->
                @if (session('success') || session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 transition-all">
                        @if (session('success'))
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/50 rounded-xl flex items-center justify-between text-emerald-800 dark:text-emerald-400">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-xl flex items-center justify-between text-red-800 dark:text-red-400">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium">{{ session('error') }}</span>
                                </div>
                                <button @click="show = false" class="text-red-500 hover:text-red-700 dark:hover:text-red-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebar-backdrop').classList.add('hidden');
        }

        // Dark mode toggle
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        // Sidebar collapse toggle for desktop
        function toggleSidebarCollapse() {
            document.getElementById('app-shell').classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed',
                document.getElementById('app-shell').classList.contains('sidebar-collapsed'));
        }
    </script>
</body>
</html>
