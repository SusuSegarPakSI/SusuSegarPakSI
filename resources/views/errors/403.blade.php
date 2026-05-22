@extends('layouts.app')

@section('title', 'Akses Ditolak')
@section('breadcrumb', 'Akses Ditolak')

@section('content')
<div class="flex flex-col items-center justify-center py-20 px-6 text-center">
    <!-- Icon / Shield -->
    <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-900/30 flex items-center justify-center mb-6 shadow-sm">
        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.599-3.75A11.952 11.952 0 0 1 12 2.715Z" />
        </svg>
    </div>

    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">403</h1>
    <h3 class="mt-2 text-lg font-semibold text-slate-800 dark:text-slate-200">
        Akses Ditolak / Dibatasi
    </h3>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-md leading-relaxed">
        Maaf, Anda tidak memiliki izin atau wewenang untuk mengakses halaman ini. Silakan hubungi Manajer/Owner jika Anda memerlukan akses tambahan.
    </p>

    <div class="mt-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
