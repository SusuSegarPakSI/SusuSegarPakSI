@extends('layouts.app')

@section('title', 'Manajemen User')
@section('breadcrumb', 'Manajemen User')

@section('content')
<div x-data="{ 
    openAdd: false, 
    openEdit: false, 
    openToggle: false, 
    editUser: { id: '', name: '', email: '' }, 
    toggleUser: { id: '', name: '', active: false, url: '' } 
}"
x-init="
    @if($errors->any() && old('_method') === 'PUT')
        openEdit = true;
        editUser = {
            id: '{{ old('user_id') }}',
            name: '{{ old('name') }}',
            email: '{{ old('email') }}'
        };
    @elseif($errors->any())
        openAdd = true;
    @endif
"
class="space-y-6">

    <!-- Header Page -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola dan pantau hak akses pengguna sistem ERP.</p>
        </div>
        <div>
            <button @click="openAdd = true" 
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Admin
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        <!-- Toolbar Tabel -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Daftar Akun Operator</h3>
                <span class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full font-mono">
                    {{ $users->total() }} total
                </span>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            Nama Lengkap
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            Alamat Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            Peran (Role)
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            Status Akun
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            Login Terakhir
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                            <!-- Nama -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">
                                {{ $user->name }}
                            </td>
                            <!-- Email -->
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                {{ $user->email }}
                            </td>
                            <!-- Role -->
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                <span class="capitalize text-xs font-semibold px-2.5 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <!-- Login Terakhir -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-500 dark:text-slate-400">
                                {{ $user->last_login_at ? $user->last_login_at->timezone('Asia/Jakarta')->format('d M Y H:i') : '-' }}
                            </td>
                            <!-- Aksi -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <!-- Edit Button -->
                                    <button @click="editUser = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}' }; openEdit = true" 
                                            class="p-1.5 rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-950/50 transition-colors" 
                                            title="Ubah Profil">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                    </button>

                                    <!-- Status Toggle Button -->
                                    <button @click="toggleUser = { id: '{{ $user->id }}', name: '{{ $user->name }}', active: {{ $user->is_active ? 'true' : 'false' }}, url: '{{ route('users.toggle', $user->id) }}' }; openToggle = true" 
                                            class="p-1.5 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors" 
                                            title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                        @if ($user->is_active)
                                            <!-- Block/Deactivate Icon -->
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        @else
                                            <!-- Reactivate/Check Icon -->
                                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    <p class="text-sm font-semibold">Tidak ada akun admin</p>
                                    <p class="text-xs text-slate-400">Belum ada akun admin tambahan yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- ═══════════════════════════════════════════ -->
    <!-- MODAL: TAMBAH ADMIN                         -->
    <!-- ═══════════════════════════════════════════ -->
    <div x-show="openAdd" class="relative z-50" aria-labelledby="modal-title" style="display: none;">
        <!-- Backdrop -->
        <div x-show="openAdd" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="openAdd = false"></div>

        <!-- Panel -->
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="openAdd"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-lg overflow-hidden transition-all">
                    
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Tambah Akun Admin Baru</h3>
                            <button type="button" @click="openAdd = false" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-5 space-y-4">
                            <!-- Name -->
                            <div class="space-y-1.5">
                                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('_method') !== 'PUT' ? old('name') : '' }}" required
                                       placeholder="Contoh: Reza Operator"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors @error('name') border-red-400 focus:ring-red-400 @enderror">
                                @if(old('_method') !== 'PUT')
                                    @error('name')
                                        <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Alamat Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('_method') !== 'PUT' ? old('email') : '' }}" required
                                       placeholder="email@example.com"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors @error('email') border-red-400 focus:ring-red-400 @enderror">
                                @if(old('_method') !== 'PUT')
                                    @error('email')
                                        <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Password -->
                            <div class="space-y-1.5">
                                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Kata Sandi <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="password" name="password" required
                                       placeholder="Minimal 8 karakter"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors @error('password') border-red-400 focus:ring-red-400 @enderror">
                                @if(old('_method') !== 'PUT')
                                    @error('password')
                                        <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Password Confirmation -->
                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                       placeholder="Ulangi kata sandi"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
                            <button type="button" @click="openAdd = false" 
                                    class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors focus:outline-none">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════ -->
    <!-- MODAL: EDIT PROFILE / PASSWORD             -->
    <!-- ═══════════════════════════════════════════ -->
    <div x-show="openEdit" class="relative z-50" aria-labelledby="modal-title" style="display: none;">
        <!-- Backdrop -->
        <div x-show="openEdit" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="openEdit = false"></div>

        <!-- Panel -->
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="openEdit"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-lg overflow-hidden transition-all">
                    
                    <form method="POST" :action="'/pengaturan/users/' + editUser.id">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" :value="editUser.id">

                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Ubah Akun Admin</h3>
                            <button type="button" @click="openEdit = false" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-5 space-y-4">
                            <!-- Name -->
                            <div class="space-y-1.5">
                                <label for="edit_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="edit_name" name="name" x-model="editUser.name" required
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors @error('name') border-red-400 focus:ring-red-400 @enderror">
                                @if(old('_method') === 'PUT')
                                    @error('name')
                                        <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="edit_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Alamat Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="edit_email" name="email" x-model="editUser.email" required
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors @error('email') border-red-400 focus:ring-red-400 @enderror">
                                @if(old('_method') === 'PUT')
                                    @error('email')
                                        <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Divider for Optional Password -->
                            <div class="pt-2">
                                <div class="relative flex py-2 items-center">
                                    <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
                                    <span class="flex-shrink mx-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider bg-white dark:bg-slate-900 px-2">Kata Sandi Baru (Opsional)</span>
                                    <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-1.5">
                                <label for="edit_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Kata Sandi Baru
                                </label>
                                <input type="password" id="edit_password" name="password"
                                       placeholder="Kosongkan jika tidak ingin mengubah"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors @error('password') border-red-400 focus:ring-red-400 @enderror">
                                @if(old('_method') === 'PUT')
                                    @error('password')
                                        <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Password Confirmation -->
                            <div class="space-y-1.5">
                                <label for="edit_password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Konfirmasi Kata Sandi Baru
                                </label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                       placeholder="Ulangi kata sandi baru"
                                       class="block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-indigo-400 transition-colors">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
                            <button type="button" @click="openEdit = false" 
                                    class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors focus:outline-none">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════ -->
    <!-- MODAL: KONFIRMASI TOGGLE STATUS             -->
    <!-- ═══════════════════════════════════════════ -->
    <div x-show="openToggle" class="relative z-50" aria-labelledby="modal-title" style="display: none;">
        <!-- Backdrop -->
        <div x-show="openToggle" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="openToggle = false"></div>

        <!-- Panel -->
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="openToggle"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden transition-all">
                    
                    <form method="POST" :action="toggleUser.url">
                        @csrf
                        @method('PATCH')

                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Konfirmasi Perubahan Status</h3>
                            <button type="button" @click="openToggle = false" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-5">
                            <div class="flex items-start gap-4">
                                <!-- Warning/Danger Icon -->
                                <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                     :class="toggleUser.active ? 'bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white"
                                        x-text="toggleUser.active ? 'Nonaktifkan Akun Admin?' : 'Aktifkan Kembali Akun Admin?'"></h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                        Apakah Anda yakin ingin <span x-text="toggleUser.active ? 'menonaktifkan' : 'mengaktifkan kembali'"></span> akun milik <strong class="text-slate-800 dark:text-slate-200" x-text="toggleUser.name"></strong>?
                                    </p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1" x-show="toggleUser.active">
                                        Pengguna tidak akan dapat masuk ke sistem ERP jika dinonaktifkan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
                            <button type="button" @click="openToggle = false" 
                                    class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors focus:outline-none">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm transition-colors focus:outline-none"
                                    :class="toggleUser.active ? 'bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500' : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500'">
                                <span x-text="toggleUser.active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
