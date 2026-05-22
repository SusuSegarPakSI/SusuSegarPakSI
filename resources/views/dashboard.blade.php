@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-slate-900 dark:to-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-sm text-white relative overflow-hidden">
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-indigo-200 bg-indigo-900/40 border border-indigo-700/50">
                ERP Susu Segar Pak Si
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-4">
                Selamat Datang Kembali, {{ auth()->user()->name }}!
            </h1>
            <p class="text-sm text-slate-300 mt-2 max-w-xl leading-relaxed">
                Anda masuk sebagai <span class="font-semibold text-indigo-300 capitalize">{{ auth()->user()->role }}</span>. 
                @role('manajer')
                Semua laporan keuangan, monitoring performa sistem, dan kontrol manajemen pengguna ada dalam wewenang Anda.
                @else
                Kelola semua data operasional harian seperti Master Data, Penjualan, Produksi, dan Persediaan dengan cepat dan efisien.
                @endrole
            </p>
        </div>
        <!-- Decorative SVG background -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/>
            </svg>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Stat Card 1 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Produksi Hari Ini
                    </p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white font-mono">
                        420 Liter
                    </p>
                </div>
                <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v1.244c0 .89-.56 1.6-1.396 1.845a9.077 9.077 0 0 1-3.69 0C3.83 5.95 3.27 5.239 3.27 4.348V3.104c0-.768.4-1.468 1.055-1.859a11.95 11.95 0 0 1 10.66 0c.655.39 1.055 1.09 1.055 1.859Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 10.5h19.5M2.25 15h19.5m-19.5 4.5h19.5" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-1.5 py-0.5 rounded-md">
                    +8.5%
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">vs kemarin</span>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Total Penjualan (Mei)
                    </p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white font-mono">
                        Rp 48.2 M
                    </p>
                </div>
                <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/50 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-1.5 py-0.5 rounded-md">
                    +12.4%
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">vs bulan lalu</span>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Bahan Baku Aktif
                    </p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white font-mono">
                        1,250 kg
                    </p>
                </div>
                <div class="p-2.5 bg-amber-50 dark:bg-amber-950/50 rounded-lg">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950/50 px-1.5 py-0.5 rounded-md">
                    -2.1%
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Ambang batas aman</span>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Karyawan Aktif
                    </p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white font-mono">
                        18 Orang
                    </p>
                </div>
                <div class="p-2.5 bg-blue-50 dark:bg-blue-950/50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a.75.75 0 0 0 0-1.5H6a.75.75 0 0 0 0 1.5h12Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 8.25a6 6 0 0 0-12 0v.75h12v-.75Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-md">
                    Tetap
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Tidak ada perubahan</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Section Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left 2 Cols: Dynamic Modules Access -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Akses Cepat Modul ERP</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar modul yang dapat Anda akses untuk operasional harian.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('master.produk.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-950/40 rounded-lg group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/40 transition-colors">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Master Data</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola produk & bahan baku.</p>
                        </div>
                    </a>

                    <a href="{{ route('penjualan.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-950/40 rounded-lg group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/40 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Penjualan</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Transaksi & pencatatan penjualan.</p>
                        </div>
                    </a>

                    <a href="{{ route('produksi.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="p-2 bg-amber-50 dark:bg-amber-950/40 rounded-lg group-hover:bg-amber-100 dark:group-hover:bg-amber-900/40 transition-colors">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v1.244c0 .89-.56 1.6-1.396 1.845a9.077 9.077 0 0 1-3.69 0" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Produksi</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pencatatan produksi susu segar.</p>
                        </div>
                    </a>

                    <a href="{{ route('persediaan.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="p-2 bg-blue-50 dark:bg-blue-950/40 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40 transition-colors">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Persediaan</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Stok gudang dan logistik.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Operational Activity Mockup -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Log Aktivitas Terbaru</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar perubahan dan log sistem dalam 24 jam terakhir.</p>
                
                <div class="mt-6 space-y-4">
                    <div class="flex gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Produksi Baru Dicatatkan</p>
                            <p class="text-xs text-slate-500 mt-0.5">Admin mencatatkan 120L Susu Murni dari Peternak A.</p>
                            <span class="text-[10px] text-slate-400 font-mono">10:14 WIB</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1.5 shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Transaksi Penjualan Lunas</p>
                            <p class="text-xs text-slate-500 mt-0.5">Invoice #INV-2026-004 telah dilunasi oleh Toko SI.</p>
                            <span class="text-[10px] text-slate-400 font-mono">08:30 WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Role Action Sidebar / Quick Settings -->
        <div class="space-y-6">
            <!-- Role-Specific Action Panel -->
            @role('manajer')
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Kontrol Manajemen</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan tautan berikut untuk memantau sistem.</p>
                
                <div class="mt-6 space-y-3">
                    <a href="{{ route('users.index') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Manajemen Pengguna</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    
                    <a href="{{ route('pengaturan.index') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Pengaturan Sistem</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Akses Read-Only</h2>
                <div class="mt-4 p-4 rounded-lg bg-slate-50 dark:bg-slate-800/30 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 text-xs leading-relaxed space-y-2">
                    <p class="font-medium text-slate-800 dark:text-slate-200">Panduan Peran Operator:</p>
                    <p>Sebagai Admin, Anda dapat mengelola transaksi & operasional secara penuh. Namun, dashboard ini hanya bersifat visualisasi data saja (Read-Only).</p>
                    <p>Silakan gunakan menu navigasi sidebar untuk melakukan perubahan data pada modul operasional.</p>
                </div>
            </div>
            @endrole

            <!-- System Info Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Status Sistem</h2>
                <div class="mt-6 space-y-4 text-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-slate-500 dark:text-slate-400">Environment</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 font-mono text-xs">{{ app()->environment() }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-slate-500 dark:text-slate-400">Database Driver</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 font-mono text-xs">{{ config('database.default') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Waktu Terakhir Login</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 font-mono text-xs">
                            {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->timezone('Asia/Jakarta')->format('d M Y H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
