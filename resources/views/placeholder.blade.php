@extends('layouts.app')

@section('title', $title)
@section('breadcrumb', $title)

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Modul ERP Susu Segar Pak Si</p>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-900/30">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Dalam Pengembangan
            </span>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
            <!-- Icon / Illustration -->
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/30 flex items-center justify-center mb-6 shadow-sm">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.68-.69-1.8-2.01-2.02-2.22l-3.07-3.1a1.5 1.5 0 0 0-2.12 0l-.74.74a1.5 1.5 0 0 0 0 2.12l3.07 3.09c.21.22 1.52 1.35 2.21 2.03l.74-.74a1.5 1.5 0 0 0 0-2.12Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.11 5.92c-.68-.69-1.8-2.01-2.02-2.22l-3.07-3.1a1.5 1.5 0 0 0-2.12 0l-.74.74a1.5 1.5 0 0 0 0 2.12l3.07 3.09c.21.22 1.52 1.35 2.21 2.03l.74-.74a1.5 1.5 0 0 0 0-2.12Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.22 13.62 5.4-5.4M13.62 19.11c-.68-.69-1.8-2.01-2.02-2.22l-3.07-3.1a1.5 1.5 0 0 0-2.12 0l-.74.74a1.5 1.5 0 0 0 0 2.12l3.07 3.09c.21.22 1.52 1.35 2.21 2.03l.74-.74a1.5 1.5 0 0 0 0-2.12Z" />
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                Halaman {{ $title }} sedang dipersiapkan
            </h3>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-md leading-relaxed">
                Kami sedang membangun fitur ini untuk membantu operasional Susu Segar Pak Si berjalan lebih efisien. Halaman ini akan segera tersedia.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 rounded-lg shadow-sm dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
