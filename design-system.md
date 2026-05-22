# Design System — Susu Segar Pak Si ERP
> **Version:** 1.0.0 · **Stack:** Laravel + Tailwind CSS v3 · **Last updated:** 2026-05-22
>
> Dokumen ini adalah **single source of truth** untuk seluruh keputusan UI/UX sistem ERP. Setiap developer wajib merujuk dokumen ini sebelum membuat komponen baru. Jangan membuat style ad-hoc; selalu gunakan token yang sudah didefinisikan di sini.

---

## Daftar Isi

1. [Filosofi & Vibe Desain](#1-filosofi--vibe-desain)
2. [Color Palette](#2-color-palette)
3. [Typography](#3-typography)
4. [Spacing & Layout](#4-spacing--layout)
5. [Komponen UI](#5-komponen-ui)
   - [5a. Layout Shell](#5a-layout-shell)
   - [5b. Cards](#5b-cards)
   - [5c. Buttons](#5c-buttons)
   - [5d. Forms](#5d-forms)
   - [5e. Tables](#5e-tables)
   - [5f. Badges & Pills](#5f-badges--pills)
   - [5g. Modals](#5g-modals)
   - [5h. Notifications](#5h-notifications)
   - [5i. Pagination](#5i-pagination)
   - [5j. Tabs](#5j-tabs)
   - [5k. Dropdown Menu](#5k-dropdown-menu)
   - [5l. Empty State](#5l-empty-state)
   - [5m. Loading Skeleton](#5m-loading-skeleton)
6. [Iconografi](#6-iconografi)
7. [Navigasi Sidebar](#7-navigasi-sidebar)
8. [Aturan UX](#8-aturan-ux)

---

## 1. Filosofi & Vibe Desain

### Karakter Visual
Sistem ini dirancang untuk terasa seperti **software enterprise kelas menengah** — bukan aplikasi kasir sederhana, bukan pula dashboard overengineered. Inspirasinya adalah Linear.app, Notion, dan Vercel Dashboard.

| Prinsip | Penjelasan |
|---|---|
| **Elegan & Profesional** | Gunakan whitespace dengan niat. Setiap elemen harus punya alasan keberadaannya. |
| **Bersih (Clean)** | Tidak ada dekorasi yang tidak fungsional. Border tipis, shadow minimal. |
| **Medium Density** | Tidak terlalu sparce (boros ruang), tidak terlalu padat. Cukup untuk scan cepat. |
| **Monochrome-First** | Palet utama adalah abu-abu (Slate). Warna hanya muncul untuk aksen, status, dan CTA. |
| **Dual Mode** | Light mode dan Dark mode didukung secara native via class `dark:` Tailwind. |

### Palet Filosofi
- **Slate** sebagai netral utama (background, teks, border)
- **Indigo** sebagai aksen tunggal (primary action, active state, link)
- Warna semantik (hijau, merah, kuning, biru) hanya untuk status dan feedback

---

## 2. Color Palette

### Cara Implementasi di `tailwind.config.js`

```js
// tailwind.config.js
module.exports = {
  darkMode: 'class', // Aktifkan dark mode via class .dark pada <html>
  theme: {
    extend: {
      colors: {
        // Aksen utama
        accent: {
          DEFAULT: '#4F46E5', // Indigo-600
          hover:   '#4338CA', // Indigo-700
          light:   '#EEF2FF', // Indigo-50
          muted:   '#818CF8', // Indigo-400 (untuk dark mode)
        },
        // Semantik
        success: { DEFAULT: '#10B981', light: '#D1FAE5', dark: '#059669' },
        danger:  { DEFAULT: '#EF4444', light: '#FEE2E2', dark: '#DC2626' },
        warning: { DEFAULT: '#F59E0B', light: '#FEF3C7', dark: '#D97706' },
        info:    { DEFAULT: '#3B82F6', light: '#DBEAFE', dark: '#2563EB' },
      },
    },
  },
}
```

### Token Warna — Light Mode

| Token | Tailwind Class | Hex | Penggunaan |
|---|---|---|---|
| **Background / Page** | `bg-slate-50` | `#F8FAFC` | Background halaman utama |
| **Surface / Card** | `bg-white` | `#FFFFFF` | Card, panel, modal, sidebar |
| **Surface Elevated** | `bg-white` + `shadow-md` | — | Card yang "mengambang" |
| **Surface Hover** | `bg-slate-50` | `#F8FAFC` | Row tabel hover, menu item hover |
| **Border Subtle** | `border-slate-200` | `#E2E8F0` | Border default card dan input |
| **Border Strong** | `border-slate-300` | `#CBD5E1` | Separator, border aktif |
| **Text Heading** | `text-slate-900` | `#0F172A` | Judul halaman, heading card |
| **Text Body** | `text-slate-700` | `#334155` | Teks konten utama |
| **Text Muted** | `text-slate-500` | `#64748B` | Label, metadata, hint |
| **Text Disabled** | `text-slate-400` | `#94A3B8` | Placeholder, field disabled |
| **Text Accent** | `text-indigo-600` | `#4F46E5` | Link, active nav item |
| **Primary Action** | `bg-indigo-600` | `#4F46E5` | Tombol primary |
| **Primary Hover** | `bg-indigo-700` | `#4338CA` | Tombol primary hover |
| **Sidebar BG** | `bg-white` | `#FFFFFF` | Background sidebar |
| **Topbar BG** | `bg-white` | `#FFFFFF` | Background navbar atas |
| **Active Nav BG** | `bg-indigo-50` | `#EEF2FF` | Background nav item aktif |

### Token Warna — Dark Mode

| Token | Tailwind Class | Hex | Penggunaan |
|---|---|---|---|
| **Background / Page** | `dark:bg-slate-950` | `#020617` | Background halaman |
| **Surface / Card** | `dark:bg-slate-900` | `#0F172A` | Card, panel |
| **Surface Elevated** | `dark:bg-slate-800` | `#1E293B` | Card elevated, modal |
| **Surface Hover** | `dark:bg-slate-800` | `#1E293B` | Row hover, menu hover |
| **Border Subtle** | `dark:border-slate-700` | `#334155` | Border default |
| **Border Strong** | `dark:border-slate-600` | `#475569` | Separator, border aktif |
| **Text Heading** | `dark:text-slate-50` | `#F8FAFC` | Heading |
| **Text Body** | `dark:text-slate-300` | `#CBD5E1` | Konten utama |
| **Text Muted** | `dark:text-slate-500` | `#64748B` | Label, metadata |
| **Text Disabled** | `dark:text-slate-600` | `#475569` | Placeholder |
| **Text Accent** | `dark:text-indigo-400` | `#818CF8` | Link, active state |
| **Primary Action** | `dark:bg-indigo-500` | `#6366F1` | Tombol primary |
| **Sidebar BG** | `dark:bg-slate-900` | `#0F172A` | Sidebar |
| **Active Nav BG** | `dark:bg-indigo-950` | `#1e1b4b` | Nav aktif |

### Warna Semantik (Light & Dark)

```html
<!-- Success -->
<span class="text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/50">
<!-- Danger -->
<span class="text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-950/50">
<!-- Warning -->
<span class="text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/50">
<!-- Info -->
<span class="text-blue-700 bg-blue-50 dark:text-blue-400 dark:bg-blue-950/50">
```

---

## 3. Typography

### Font Loading (di `resources/css/app.css` atau `<head>`)

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
```

```js
// tailwind.config.js — fontFamily
fontFamily: {
  sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
  mono: ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'monospace'],
},
```

### Type Scale

| Level | Size | Line Height | Letter Spacing | Weight | Tailwind Class | Penggunaan |
|---|---|---|---|---|---|---|
| **Display** | 36px | 44px (1.22) | -0.02em | 700 | `text-4xl font-bold tracking-tight leading-tight` | Hero section, angka KPI besar |
| **H1** | 28px | 36px (1.29) | -0.01em | 700 | `text-3xl font-bold tracking-tight` | Judul halaman utama |
| **H2** | 22px | 30px (1.36) | -0.01em | 600 | `text-2xl font-semibold tracking-tight` | Judul section, judul card |
| **H3** | 18px | 26px (1.44) | 0em | 600 | `text-lg font-semibold` | Sub-section, judul grup |
| **H4** | 16px | 24px (1.5) | 0em | 500 | `text-base font-medium` | Label penting, judul kecil |
| **Body** | 14px | 22px (1.57) | 0em | 400 | `text-sm` | Teks konten utama |
| **Small** | 12px | 18px (1.5) | 0em | 400 | `text-xs` | Metadata, label, caption |
| **Tiny** | 11px | 16px (1.45) | 0.01em | 400 | `text-[11px] tracking-wide` | Badge, timestamp, tag |
| **Mono** | 13px | 20px | 0em | 400/500 | `font-mono text-[13px]` | Angka finansial, kode, ID |

### Aturan Tipografi

```
✅ Gunakan `font-mono` untuk: harga (Rp), quantity, kode produk, nomor invoice
✅ Heading selalu tracking-tight (-0.01em atau -0.02em)
✅ Body text maksimal 72 karakter per baris untuk readability optimal
✅ Jangan gunakan warna teks lebih dari 3 level dalam satu halaman
```

---

## 4. Spacing & Layout

### System Spacing (Base Unit: 4px)

```
4px  = space-1  (gap kecil antar elemen inline)
8px  = space-2  (padding badge, gap ikon-teks)
12px = space-3  (padding cell tabel kecil)
16px = space-4  (padding card kecil, gap form field)
20px = space-5  (padding komponen medium)
24px = space-6  (padding card standar, content area)
32px = space-8  (gap antar section)
40px = space-10 (margin besar)
48px = space-12 (padding section hero)
64px = space-16 (section spacing besar)
```

### Layout Utama

| Elemen | Ukuran | Tailwind |
|---|---|---|
| **Container max-width** | 1440px | `max-w-screen-2xl mx-auto` |
| **Padding horizontal** | 24px | `px-6` |
| **Sidebar (expanded)** | 240px | `w-60` |
| **Sidebar (collapsed)** | 64px | `w-16` |
| **Topbar height** | 56px | `h-14` |
| **Content area padding** | 24px | `p-6` |
| **Content area padding (mobile)** | 16px | `p-4` |
| **Card padding (default)** | 24px | `p-6` |
| **Card padding (compact)** | 16px | `p-4` |

### Grid System

```html
<!-- Grid 3 kolom (stat cards) -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

<!-- Grid 2 kolom (form + sidebar info) -->
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
  <div class="lg:col-span-2"><!-- Form --></div>
  <div><!-- Info panel --></div>
</div>
```

### Breakpoints

| Breakpoint | Value | Perilaku |
|---|---|---|
| `sm` | 640px | 1 kolom → 2 kolom |
| `md` | 768px | Layout mobile → tablet |
| `lg` | 1024px | Sidebar otomatis collapse, full desktop |
| `xl` | 1280px | Grid lebar |
| `2xl` | 1536px | Container max-width aktif |

---

## 5. Komponen UI

---

### 5a. Layout Shell

#### Struktur HTML Utama

```html
<!-- resources/views/layouts/app.blade.php -->
<body class="bg-slate-50 dark:bg-slate-950 font-sans antialiased">

  <!-- Wrapper utama -->
  <div class="flex h-screen overflow-hidden" id="app-shell">

    <!-- ═══════════════════════════════════════════ -->
    <!-- SIDEBAR                                     -->
    <!-- ═══════════════════════════════════════════ -->
    <aside id="sidebar"
      class="flex flex-col w-60 shrink-0 border-r border-slate-200
             bg-white dark:bg-slate-900 dark:border-slate-700
             transition-all duration-200 ease-in-out
             lg:relative lg:translate-x-0
             fixed inset-y-0 left-0 z-50
             -translate-x-full lg:translate-x-0"
      aria-label="Sidebar navigasi">

      <!-- Logo / Brand -->
      <div class="flex items-center h-14 px-4 border-b border-slate-200 dark:border-slate-700 shrink-0">
        <div class="flex items-center gap-2.5">
          <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><!-- ikon susu --></svg>
          </div>
          <span class="text-sm font-semibold text-slate-900 dark:text-slate-50 sidebar-label">
            Susu Segar Pak Si
          </span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-0.5">
        <!-- Nav items — lihat Bagian 7 -->
      </nav>

      <!-- User profile (bottom) -->
      <div class="shrink-0 border-t border-slate-200 dark:border-slate-700 p-3">
        <button class="w-full flex items-center gap-3 px-2 py-2 rounded-lg
                       hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900
                      flex items-center justify-center shrink-0">
            <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">PS</span>
          </div>
          <div class="text-left min-w-0 sidebar-label">
            <p class="text-xs font-medium text-slate-900 dark:text-slate-50 truncate">Pak Si</p>
            <p class="text-[11px] text-slate-500 dark:text-slate-500 truncate">Admin</p>
          </div>
        </button>
      </div>
    </aside>

    <!-- Backdrop (mobile) -->
    <div id="sidebar-backdrop"
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden"
         onclick="closeSidebar()"></div>

    <!-- ═══════════════════════════════════════════ -->
    <!-- MAIN AREA                                   -->
    <!-- ═══════════════════════════════════════════ -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

      <!-- TOP BAR -->
      <header class="flex items-center justify-between h-14 px-4 sm:px-6
                     border-b border-slate-200 dark:border-slate-700
                     bg-white dark:bg-slate-900 shrink-0 z-30">

        <!-- Kiri: Hamburger + Breadcrumb -->
        <div class="flex items-center gap-3">
          <!-- Hamburger (mobile) -->
          <button onclick="toggleSidebar()"
                  class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100
                         dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
          </button>

          <!-- Breadcrumb -->
          <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-500">
              <li><a href="#" class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">Dashboard</a></li>
              <li><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
              <li class="text-slate-700 dark:text-slate-300 font-medium">Penjualan</li>
            </ol>
          </nav>
        </div>

        <!-- Kanan: Actions -->
        <div class="flex items-center gap-2">
          <!-- Dark Mode Toggle -->
          <button onclick="toggleDarkMode()"
                  class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100
                         dark:hover:bg-slate-800 transition-colors" aria-label="Toggle dark mode">
            <svg class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            </svg>
            <svg class="w-5 h-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
          </button>

          <!-- Notifikasi -->
          <button class="relative p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
            </svg>
            <!-- Badge notifikasi -->
            <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
          </button>
        </div>
      </header>

      <!-- CONTENT AREA -->
      <main class="flex-1 overflow-y-auto p-6 space-y-6">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Toast container -->
  <div id="toast-container"
       class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none"
       aria-live="polite"></div>

</body>
```

#### JavaScript Sidebar

```javascript
// Sidebar toggle
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

// Dark mode
function toggleDarkMode() {
  document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
}
// Init on load
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark');
}
```

---

### 5b. Cards

#### Default Card

```html
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
            rounded-xl p-6 shadow-sm">
  <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Judul Card</h3>
  <p class="mt-1 text-sm text-slate-500 dark:text-slate-500">Konten card di sini.</p>
</div>
```

#### Elevated Card

```html
<div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-md
            ring-1 ring-slate-900/5 dark:ring-slate-700">
  <!-- Konten -->
</div>
```

#### Bordered Card (tanpa shadow)

```html
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
            rounded-xl p-6">
  <!-- Konten -->
</div>
```

#### Stat Card (dengan trend indicator)

```html
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
            rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
  <div class="flex items-start justify-between">
    <div>
      <p class="text-xs font-medium text-slate-500 dark:text-slate-500 uppercase tracking-wider">
        Total Penjualan
      </p>
      <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-50 font-mono">
        Rp 12,4 Jt
      </p>
    </div>
    <!-- Ikon -->
    <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-lg">
      <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
      </svg>
    </div>
  </div>

  <!-- Trend Indicator -->
  <div class="mt-4 flex items-center gap-1.5">
    <!-- ▲ Naik -->
    <span class="inline-flex items-center gap-0.5 text-xs font-medium text-emerald-700
                 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50
                 px-1.5 py-0.5 rounded-md">
      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18"/>
      </svg>
      +12,5%
    </span>
    <!-- ▼ Turun (alternatif) -->
    {{-- <span class="inline-flex items-center gap-0.5 text-xs font-medium text-red-700
                 dark:text-red-400 bg-red-50 dark:bg-red-950/50 px-1.5 py-0.5 rounded-md">
      <svg class="w-3 h-3" ...>↓</svg>
      -3,2%
    </span> --}}
    <span class="text-xs text-slate-500 dark:text-slate-500">vs bulan lalu</span>
  </div>
</div>
```

---

### 5c. Buttons

#### Semua Varian Tombol

```html
<!-- PRIMARY -->
<button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
               bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
               text-white rounded-lg shadow-sm transition-all duration-150
               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
               dark:focus:ring-offset-slate-900
               disabled:opacity-50 disabled:cursor-not-allowed">
  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
  </svg>
  Tambah Data
</button>

<!-- SECONDARY -->
<button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
               bg-slate-100 hover:bg-slate-200 active:bg-slate-300
               text-slate-700 rounded-lg shadow-sm transition-all duration-150
               dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200
               focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2
               dark:focus:ring-offset-slate-900
               disabled:opacity-50 disabled:cursor-not-allowed">
  Ekspor
</button>

<!-- GHOST -->
<button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
               text-slate-600 hover:bg-slate-100 active:bg-slate-200
               dark:text-slate-300 dark:hover:bg-slate-800 dark:active:bg-slate-700
               rounded-lg transition-all duration-150
               focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
  Batal
</button>

<!-- OUTLINE -->
<button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
               border border-slate-300 dark:border-slate-600
               text-slate-700 dark:text-slate-300
               hover:bg-slate-50 dark:hover:bg-slate-800
               rounded-lg transition-all duration-150
               focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
  Filter
</button>

<!-- DANGER -->
<button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
               bg-red-600 hover:bg-red-700 active:bg-red-800
               text-white rounded-lg shadow-sm transition-all duration-150
               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
               dark:focus:ring-offset-slate-900">
  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
  </svg>
  Hapus
</button>

<!-- ICON-ONLY -->
<button class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100
               dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800
               rounded-lg transition-all duration-150
               focus:outline-none focus:ring-2 focus:ring-slate-400"
        aria-label="Edit data">
  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
  </svg>
</button>

<!-- LOADING STATE (gunakan Alpine.js atau JavaScript) -->
<button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
               bg-indigo-600 text-white rounded-lg
               opacity-75 cursor-not-allowed" disabled>
  <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
  </svg>
  Menyimpan...
</button>
```

#### Ukuran Tombol

| Ukuran | Class | Penggunaan |
|---|---|---|
| **xs** | `px-2.5 py-1 text-xs` | Badge-like action, action dalam row tabel |
| **sm** | `px-3 py-1.5 text-sm` | Toolbar, action sekunder |
| **md (default)** | `px-4 py-2 text-sm` | Primary action halaman |
| **lg** | `px-5 py-2.5 text-base` | CTA utama, hero button |

---

### 5d. Forms

#### Input Text

```html
<div class="space-y-1.5">
  <label for="nama_pelanggan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
    Nama Pelanggan
    <span class="text-red-500 ml-0.5">*</span>
  </label>
  <div class="relative">
    <!-- Opsional: ikon kiri -->
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
      </svg>
    </div>
    <input type="text"
           id="nama_pelanggan"
           name="nama_pelanggan"
           placeholder="Masukkan nama lengkap"
           class="block w-full pl-10 pr-3 py-2 text-sm
                  bg-white dark:bg-slate-900
                  border border-slate-300 dark:border-slate-600
                  text-slate-900 dark:text-slate-50
                  placeholder-slate-400 dark:placeholder-slate-600
                  rounded-lg shadow-sm
                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                  dark:focus:ring-indigo-400
                  disabled:bg-slate-50 dark:disabled:bg-slate-800
                  disabled:text-slate-400 disabled:cursor-not-allowed
                  transition-colors">
  </div>
  <!-- Error state -->
  <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
    </svg>
    Nama pelanggan wajib diisi.
  </p>
  <!-- Hint text -->
  <p class="text-xs text-slate-500 dark:text-slate-500">Masukkan nama sesuai KTP.</p>
</div>
```

**State border modifier:**
- Default: `border-slate-300 dark:border-slate-600`
- Focus: (ditangani oleh `focus:ring-2 focus:ring-indigo-500`)
- Error: tambahkan `border-red-400 dark:border-red-500 focus:ring-red-400`
- Success: `border-emerald-400 dark:border-emerald-500 focus:ring-emerald-400`

#### Textarea

```html
<div class="space-y-1.5">
  <label for="catatan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
    Catatan
  </label>
  <textarea id="catatan" name="catatan" rows="4"
            placeholder="Tulis catatan tambahan..."
            class="block w-full px-3 py-2 text-sm
                   bg-white dark:bg-slate-900
                   border border-slate-300 dark:border-slate-600
                   text-slate-900 dark:text-slate-50
                   placeholder-slate-400 dark:placeholder-slate-600
                   rounded-lg shadow-sm resize-y
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                   transition-colors"></textarea>
</div>
```

#### Select / Dropdown

```html
<div class="space-y-1.5">
  <label for="kategori" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
    Kategori Produk
  </label>
  <div class="relative">
    <select id="kategori" name="kategori"
            class="block w-full appearance-none px-3 py-2 pr-8 text-sm
                   bg-white dark:bg-slate-900
                   border border-slate-300 dark:border-slate-600
                   text-slate-900 dark:text-slate-50
                   rounded-lg shadow-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                   transition-colors">
      <option value="" disabled selected>Pilih kategori...</option>
      <option value="susu_murni">Susu Murni</option>
      <option value="susu_pasteurisasi">Susu Pasteurisasi</option>
    </select>
    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
      <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
      </svg>
    </div>
  </div>
</div>
```

#### Search Input

```html
<div class="relative">
  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
    </svg>
  </div>
  <input type="search" placeholder="Cari produk, pelanggan..."
         class="block w-full pl-9 pr-4 py-2 text-sm
                bg-white dark:bg-slate-900
                border border-slate-300 dark:border-slate-600
                text-slate-900 dark:text-slate-50
                placeholder-slate-400 dark:placeholder-slate-600
                rounded-lg shadow-sm
                focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                transition-colors">
</div>
```

#### Checkbox

```html
<label class="flex items-start gap-3 cursor-pointer group">
  <div class="relative flex items-center mt-0.5">
    <input type="checkbox" id="aktif" name="aktif"
           class="w-4 h-4 rounded border-slate-300 dark:border-slate-600
                  text-indigo-600 dark:bg-slate-900
                  focus:ring-indigo-500 focus:ring-offset-0
                  cursor-pointer transition-colors">
  </div>
  <div>
    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-slate-100">
      Status Aktif
    </p>
    <p class="text-xs text-slate-500 dark:text-slate-500">Pelanggan dapat melakukan transaksi.</p>
  </div>
</label>
```

#### Radio Button

```html
<fieldset class="space-y-2">
  <legend class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Jenis Pembayaran</legend>

  <label class="flex items-center gap-3 cursor-pointer group">
    <input type="radio" name="pembayaran" value="tunai"
           class="w-4 h-4 border-slate-300 dark:border-slate-600
                  text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0
                  dark:bg-slate-900 cursor-pointer">
    <span class="text-sm text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-slate-100">
      Tunai
    </span>
  </label>

  <label class="flex items-center gap-3 cursor-pointer group">
    <input type="radio" name="pembayaran" value="transfer"
           class="w-4 h-4 border-slate-300 dark:border-slate-600
                  text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0
                  dark:bg-slate-900 cursor-pointer">
    <span class="text-sm text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-slate-100">
      Transfer Bank
    </span>
  </label>
</fieldset>
```

#### Toggle / Switch

```html
<!-- Implementasi dengan Alpine.js -->
<div x-data="{ enabled: false }" class="flex items-center gap-3">
  <button @click="enabled = !enabled"
          :class="enabled
            ? 'bg-indigo-600 dark:bg-indigo-500'
            : 'bg-slate-200 dark:bg-slate-700'"
          class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full
                 border-2 border-transparent transition-colors duration-200 ease-in-out
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                 dark:focus:ring-offset-slate-900"
          role="switch" :aria-checked="enabled">
    <span :class="enabled ? 'translate-x-4' : 'translate-x-0'"
          class="pointer-events-none inline-block h-4 w-4 transform rounded-full
                 bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
  </button>
  <span class="text-sm font-medium text-slate-700 dark:text-slate-300"
        x-text="enabled ? 'Aktif' : 'Nonaktif'"></span>
</div>
```

#### Form Section Layout

```html
<!-- Layout form dengan section -->
<form class="space-y-8">
  <!-- Section -->
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
      <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Informasi Dasar</h3>
      <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-500">Isi data pokok pelanggan.</p>
    </div>
    <div class="px-6 py-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
      <!-- form fields -->
    </div>
  </div>

  <!-- Form Actions -->
  <div class="flex items-center justify-end gap-3 pt-2">
    <a href="{{ route('back') }}"
       class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300
              hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
      Batal
    </a>
    <button type="submit"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
                   bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                   dark:focus:ring-offset-slate-900 transition-all">
      Simpan Perubahan
    </button>
  </div>
</form>
```

---

### 5e. Tables

#### Tabel Standar

```html
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
            rounded-xl shadow-sm overflow-hidden">

  <!-- Toolbar Tabel -->
  <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
    <div class="flex items-center gap-3">
      <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Daftar Produk</h3>
      <span class="text-xs text-slate-500 dark:text-slate-500 bg-slate-100 dark:bg-slate-800
                   px-2 py-0.5 rounded-full">
        124 item
      </span>
    </div>
    <div class="flex items-center gap-2">
      <!-- Search -->
      <div class="relative hidden sm:block">
        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
          <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
          </svg>
        </div>
        <input type="search" placeholder="Cari..."
               class="pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600
                      bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50
                      placeholder-slate-400 dark:placeholder-slate-600 w-48
                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
      </div>
      <!-- Action Buttons -->
      <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                     border border-slate-300 dark:border-slate-600 rounded-lg
                     text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800
                     transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
        Ekspor
      </button>
      <a href="{{ route('products.create') }}"
         class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm
                transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah
      </a>
    </div>
  </div>

  <!-- Table dengan sticky header -->
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
          <!-- Checkbox kolom -->
          <th class="w-10 px-4 py-3">
            <input type="checkbox"
                   class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600
                          text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
            Kode Produk
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
            Nama Produk
          </th>
          <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
            Harga
          </th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
            Status
          </th>
          <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
            Aksi
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
        <!-- Row normal -->
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
          <td class="px-4 py-3">
            <input type="checkbox"
                   class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600
                          text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
          </td>
          <td class="px-4 py-3">
            <span class="font-mono text-xs text-slate-600 dark:text-slate-400 bg-slate-100
                         dark:bg-slate-800 px-2 py-0.5 rounded">
              PRD-001
            </span>
          </td>
          <td class="px-4 py-3">
            <p class="font-medium text-slate-900 dark:text-slate-50">Susu Segar 1L</p>
            <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">Susu Murni</p>
          </td>
          <td class="px-4 py-3 text-right font-mono text-slate-900 dark:text-slate-50">
            Rp 8.000
          </td>
          <td class="px-4 py-3 text-center">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                         text-emerald-700 dark:text-emerald-400
                         bg-emerald-50 dark:bg-emerald-950/50">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
              Aktif
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <a href="#" class="p-1.5 rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50
                                dark:hover:text-indigo-400 dark:hover:bg-indigo-950/50 transition-colors"
                 title="Edit">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
              </a>
              <button class="p-1.5 rounded text-slate-400 hover:text-red-600 hover:bg-red-50
                             dark:hover:text-red-400 dark:hover:bg-red-950/50 transition-colors"
                      title="Hapus" onclick="confirmDelete(this)" data-id="1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Tabel Footer (Pagination ditempatkan di sini — lihat 5i) -->
  <div class="px-6 py-3 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
    <p class="text-xs text-slate-500 dark:text-slate-500">
      Menampilkan <span class="font-medium text-slate-700 dark:text-slate-300">1–15</span>
      dari <span class="font-medium text-slate-700 dark:text-slate-300">124</span> data
    </p>
    <!-- Komponen Pagination di sini -->
  </div>
</div>
```

---

### 5f. Badges & Pills

#### Status Badge (Active/Inactive)

```html
<!-- Aktif -->
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
             text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/50">
  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
  Aktif
</span>

<!-- Nonaktif -->
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
             text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800">
  <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
  Nonaktif
</span>
```

#### Semantic Badges

```html
<!-- Success -->
<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium
             text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/50">
  Lunas
</span>

<!-- Danger -->
<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium
             text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-950/50">
  Jatuh Tempo
</span>

<!-- Warning -->
<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium
             text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/50">
  Menunggu
</span>

<!-- Info -->
<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium
             text-blue-700 bg-blue-50 dark:text-blue-400 dark:bg-blue-950/50">
  Dikirim
</span>

<!-- Neutral -->
<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium
             text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800">
  Draft
</span>
```

---

### 5g. Modals

#### Blade Component: `resources/views/components/modal.blade.php`

```html
<!-- Implementasi dengan Alpine.js -->
<!-- Contoh penggunaan:
     <x-modal id="modal-hapus" size="sm">
       <x-slot name="title">Konfirmasi Hapus</x-slot>
       <x-slot name="body">...</x-slot>
       <x-slot name="footer">...</x-slot>
     </x-modal>
-->

<!-- Modal SM -->
<div x-data="{ open: false }" x-show="open" class="relative z-50" aria-labelledby="modal-title">
  <!-- Backdrop -->
  <div x-show="open"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
       @click="open = false"></div>

  <!-- Modal Panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div x-show="open"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95"

           {{-- SIZE VARIANTS:
                sm:  max-w-sm (384px)
                md:  max-w-lg (512px)  ← default
                lg:  max-w-2xl (672px) --}}
           class="relative bg-white dark:bg-slate-900
                  rounded-xl shadow-xl ring-1 ring-slate-900/10 dark:ring-slate-700
                  w-full max-w-lg
                  transition-all">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4
                    border-b border-slate-200 dark:border-slate-700">
          <h3 id="modal-title"
              class="text-base font-semibold text-slate-900 dark:text-slate-50">
            Konfirmasi Hapus
          </h3>
          <button @click="open = false"
                  class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-300
                         hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5">
          <div class="flex items-start gap-4">
            <div class="shrink-0 w-10 h-10 rounded-full bg-red-50 dark:bg-red-950/50
                        flex items-center justify-center">
              <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                   viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-900 dark:text-slate-50">
                Hapus data ini secara permanen?
              </p>
              <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Data <strong class="font-medium text-slate-700 dark:text-slate-300">Susu Segar 1L</strong>
                akan dihapus dan tidak dapat dikembalikan.
              </p>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4
                    border-t border-slate-200 dark:border-slate-700">
          <button @click="open = false"
                  class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300
                         hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
            Batal
          </button>
          <form method="POST" action="{{ route('products.destroy', $product) }}">
            @csrf @method('DELETE')
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700
                           text-white rounded-lg shadow-sm transition-colors">
              Hapus Permanen
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
```

---

### 5h. Notifications

#### Toast (JavaScript)

```javascript
// Fungsi global di app.js
function showToast(message, type = 'success', duration = 4000) {
  const container = document.getElementById('toast-container');
  const id = 'toast-' + Date.now();

  const icons = {
    success: `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>`,
    error:   `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>`,
    warning: `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>`,
    info:    `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>`,
  };

  const colorMap = {
    success: 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950 border-emerald-200 dark:border-emerald-800/50',
    error:   'text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-950 border-red-200 dark:border-red-800/50',
    warning: 'text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950 border-amber-200 dark:border-amber-800/50',
    info:    'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800/50',
  };

  const toast = document.createElement('div');
  toast.id = id;
  toast.className = `pointer-events-auto flex items-start gap-3 w-80 max-w-sm px-4 py-3
                     rounded-lg border shadow-lg backdrop-blur-sm
                     translate-x-0 opacity-0 transition-all duration-300
                     ${colorMap[type]}`;
  toast.innerHTML = `
    <div class="shrink-0 mt-0.5">${icons[type]}</div>
    <p class="text-sm font-medium flex-1 leading-snug">${message}</p>
    <button onclick="document.getElementById('${id}').remove()"
            class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">
      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
      </svg>
    </button>`;

  container.appendChild(toast);
  requestAnimationFrame(() => {
    toast.classList.replace('opacity-0', 'opacity-100');
    toast.classList.replace('translate-x-0', '-translate-x-0');
  });

  setTimeout(() => {
    toast.classList.add('opacity-0', 'translate-x-4');
    setTimeout(() => toast.remove(), 300);
  }, duration);
}
```

```html
<!-- Flash message dari Laravel session (di layout utama) -->
@if (session('success'))
  <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('success') }}', 'success'))</script>
@endif
@if (session('error'))
  <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'))</script>
@endif
@if (session('warning'))
  <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('warning') }}', 'warning'))</script>
@endif
```

#### Alert Banner (Inline)

```html
<!-- Info Banner -->
<div class="flex items-start gap-3 px-4 py-3 rounded-lg
            bg-blue-50 dark:bg-blue-950/50
            border border-blue-200 dark:border-blue-800/50
            text-blue-800 dark:text-blue-300"
     role="alert">
  <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
  </svg>
  <div class="text-sm">
    <p class="font-medium">Perhatian</p>
    <p class="mt-0.5 opacity-80">Stok bahan baku hampir habis. Segera lakukan pembelian.</p>
  </div>
  <!-- Dismissible -->
  <button class="ml-auto shrink-0 opacity-60 hover:opacity-100 transition-opacity">
    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
    </svg>
  </button>
</div>
```

---

### 5i. Pagination

#### Blade Component: `resources/views/components/pagination.blade.php`

```html
{{-- Gunakan {{ $data->links('components.pagination') }} --}}
@if ($paginator->hasPages())
<nav class="flex items-center justify-between" aria-label="Pagination">
  <!-- Mobile: Prev/Next saja -->
  <div class="flex items-center gap-2 sm:hidden">
    @if ($paginator->onFirstPage())
      <span class="px-3 py-1.5 text-xs font-medium text-slate-400 dark:text-slate-600
                   bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                   rounded-lg cursor-not-allowed">Sebelumnya</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}"
         class="px-3 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300
                bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600
                hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
        Sebelumnya
      </a>
    @endif

    <span class="text-xs text-slate-500 dark:text-slate-500">
      Halaman {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
    </span>

    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}"
         class="px-3 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300
                bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600
                hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
        Berikutnya
      </a>
    @else
      <span class="px-3 py-1.5 text-xs font-medium text-slate-400 dark:text-slate-600
                   bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                   rounded-lg cursor-not-allowed">Berikutnya</span>
    @endif
  </div>

  <!-- Desktop: Numbered -->
  <div class="hidden sm:flex items-center gap-1">
    {{-- Tombol Sebelumnya --}}
    @if ($paginator->onFirstPage())
      <span class="p-1.5 text-slate-400 dark:text-slate-600 cursor-not-allowed rounded-lg">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
      </span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}"
         class="p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200
                hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
      </a>
    @endif

    {{-- Nomor halaman --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="px-2 py-1.5 text-xs text-slate-400 dark:text-slate-600">···</span>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="w-8 h-8 flex items-center justify-center text-xs font-semibold
                         text-white bg-indigo-600 dark:bg-indigo-500 rounded-lg">
              {{ $page }}
            </span>
          @else
            <a href="{{ $url }}"
               class="w-8 h-8 flex items-center justify-center text-xs text-slate-600 dark:text-slate-400
                      hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
              {{ $page }}
            </a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Tombol Berikutnya --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}"
         class="p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200
                hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
      </a>
    @else
      <span class="p-1.5 text-slate-400 dark:text-slate-600 cursor-not-allowed rounded-lg">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
      </span>
    @endif
  </div>
</nav>
@endif
```

---

### 5j. Tabs

#### Underline Style (default untuk page-level tabs)

```html
<div x-data="{ activeTab: 'overview' }">
  <!-- Tab Headers -->
  <div class="border-b border-slate-200 dark:border-slate-700">
    <nav class="-mb-px flex gap-0.5 overflow-x-auto" aria-label="Tabs">
      <button @click="activeTab = 'overview'"
              :class="activeTab === 'overview'
                ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400'
                : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600'"
              class="flex items-center gap-2 px-4 py-3 text-sm font-medium
                     border-b-2 whitespace-nowrap transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
        </svg>
        Ringkasan
      </button>

      <button @click="activeTab = 'transactions'"
              :class="activeTab === 'transactions'
                ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400'
                : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
              class="flex items-center gap-2 px-4 py-3 text-sm font-medium
                     border-b-2 whitespace-nowrap transition-colors">
        Transaksi
        <span class="text-[11px] font-medium bg-slate-100 dark:bg-slate-800
                     text-slate-600 dark:text-slate-400 px-1.5 py-0.5 rounded-full">
          42
        </span>
      </button>
    </nav>
  </div>

  <!-- Tab Content -->
  <div class="py-6">
    <div x-show="activeTab === 'overview'"><!-- Konten Overview --></div>
    <div x-show="activeTab === 'transactions'"><!-- Konten Transaksi --></div>
  </div>
</div>
```

#### Pill Style (untuk filter/toggle dalam card)

```html
<div x-data="{ period: 'month' }"
     class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-slate-800 rounded-lg">
  <button @click="period = 'week'"
          :class="period === 'week' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 shadow-sm' : 'text-slate-600 dark:text-slate-400'"
          class="px-3 py-1.5 text-xs font-medium rounded-md transition-all">
    Minggu Ini
  </button>
  <button @click="period = 'month'"
          :class="period === 'month' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 shadow-sm' : 'text-slate-600 dark:text-slate-400'"
          class="px-3 py-1.5 text-xs font-medium rounded-md transition-all">
    Bulan Ini
  </button>
  <button @click="period = 'year'"
          :class="period === 'year' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 shadow-sm' : 'text-slate-600 dark:text-slate-400'"
          class="px-3 py-1.5 text-xs font-medium rounded-md transition-all">
    Tahun Ini
  </button>
</div>
```

---

### 5k. Dropdown Menu

```html
<div x-data="{ open: false }" class="relative">
  <!-- Trigger -->
  <button @click="open = !open" @click.outside="open = false"
          class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium
                 text-slate-700 dark:text-slate-300
                 bg-white dark:bg-slate-900
                 border border-slate-300 dark:border-slate-600
                 hover:bg-slate-50 dark:hover:bg-slate-800
                 rounded-lg shadow-sm transition-colors
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
    Aksi
    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"
         fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
    </svg>
  </button>

  <!-- Dropdown Panel -->
  <div x-show="open"
       x-transition:enter="transition ease-out duration-100"
       x-transition:enter-start="opacity-0 scale-95"
       x-transition:enter-end="opacity-100 scale-100"
       x-transition:leave="transition ease-in duration-75"
       x-transition:leave-start="opacity-100 scale-100"
       x-transition:leave-end="opacity-0 scale-95"
       class="absolute right-0 mt-1.5 w-48 z-20
              bg-white dark:bg-slate-900
              border border-slate-200 dark:border-slate-700
              rounded-xl shadow-lg ring-1 ring-slate-900/5 dark:ring-slate-700/50
              overflow-hidden">

    <!-- Section Header (opsional) -->
    <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-700/50">
      <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
        Opsi
      </p>
    </div>

    <div class="p-1.5 space-y-0.5">
      <!-- Item normal -->
      <a href="#"
         class="flex items-center gap-2.5 px-2.5 py-2 text-sm text-slate-700 dark:text-slate-300
                hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
        </svg>
        Edit Data
      </a>

      <a href="#"
         class="flex items-center gap-2.5 px-2.5 py-2 text-sm text-slate-700 dark:text-slate-300
                hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"/>
        </svg>
        Duplikat
      </a>

      <!-- Separator -->
      <hr class="border-slate-100 dark:border-slate-700/50 my-1">

      <!-- Item Danger -->
      <button class="flex w-full items-center gap-2.5 px-2.5 py-2 text-sm
                     text-red-600 dark:text-red-400
                     hover:bg-red-50 dark:hover:bg-red-950/50 rounded-lg transition-colors">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
        </svg>
        Hapus Data
      </button>
    </div>
  </div>
</div>
```

---

### 5l. Empty State

```html
<div class="flex flex-col items-center justify-center py-16 px-6 text-center">
  <!-- Ilustrasi (ikon besar) -->
  <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800
              flex items-center justify-center mb-4">
    <svg class="w-8 h-8 text-slate-400 dark:text-slate-600" fill="none"
         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
    </svg>
  </div>

  <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">
    Belum ada produk
  </h3>
  <p class="mt-1 text-sm text-slate-500 dark:text-slate-500 max-w-sm">
    Mulai tambahkan produk pertama Anda untuk mengelola stok dan harga secara terpusat.
  </p>

  <div class="mt-6">
    <a href="{{ route('products.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
              bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm
              transition-colors">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
      </svg>
      Tambah Produk Pertama
    </a>
  </div>
</div>
```

---

### 5m. Loading Skeleton

#### Skeleton Card

```html
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
            rounded-xl p-6 shadow-sm animate-pulse">
  <div class="flex items-start justify-between">
    <div class="space-y-2">
      <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full w-24"></div>
      <div class="h-8 bg-slate-200 dark:bg-slate-700 rounded-full w-32"></div>
    </div>
    <div class="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded-lg"></div>
  </div>
  <div class="mt-4 h-3 bg-slate-200 dark:bg-slate-700 rounded-full w-20"></div>
</div>
```

#### Skeleton Table Rows

```html
<tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
  @for ($i = 0; $i < 8; $i++)
  <tr class="animate-pulse">
    <td class="px-4 py-3.5"><div class="w-4 h-4 bg-slate-200 dark:bg-slate-700 rounded"></div></td>
    <td class="px-4 py-3.5"><div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full w-20"></div></td>
    <td class="px-4 py-3.5">
      <div class="space-y-1.5">
        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full w-36"></div>
        <div class="h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full w-20"></div>
      </div>
    </td>
    <td class="px-4 py-3.5"><div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full w-16 ml-auto"></div></td>
    <td class="px-4 py-3.5"><div class="h-5 bg-slate-200 dark:bg-slate-700 rounded-full w-14 mx-auto"></div></td>
    <td class="px-4 py-3.5"><div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full w-12 ml-auto"></div></td>
  </tr>
  @endfor
</tbody>
```

---

## 6. Iconografi

### Library: Heroicons (Outline Style)

Gunakan **Heroicons Outline** secara eksklusif. Heroicons dibuat oleh tim Tailwind CSS dan selaras sempurna dengan estetika sistem ini.

### Cara Load via CDN

```html
<!-- Di <head> layout utama -->
<!-- Tidak perlu CDN — gunakan SVG inline atau Blade component -->
```

### Cara Load via npm (Recommended untuk Production)

```bash
npm install @heroicons/react  # atau gunakan SVG manual
```

### Cara Pakai via Blade Component (Recommended)

**Install package:**
```bash
composer require blade-ui-kit/blade-heroicons
```

**Penggunaan:**
```html
<!-- Ikon 16px (dalam teks, tombol kecil) -->
<x-heroicon-o-home class="w-4 h-4 text-slate-500" />

<!-- Ikon 20px (default untuk UI) -->
<x-heroicon-o-home class="w-5 h-5 text-slate-500" />

<!-- Ikon 24px (display, stat card) -->
<x-heroicon-o-home class="w-6 h-6 text-indigo-600" />
```

### Atau Inline SVG (Fallback)

Ambil SVG dari: [heroicons.com](https://heroicons.com)

### Ukuran Standar

| Konteks | Size | Class |
|---|---|---|
| Badge, row action | 14px | `w-3.5 h-3.5` |
| Tombol, input icon | 16px | `w-4 h-4` |
| Nav sidebar, default | 20px | `w-5 h-5` |
| Stat card icon | 20–24px | `w-5 h-5` atau `w-6 h-6` |
| Empty state | 32px | `w-8 h-8` |

---

## 7. Navigasi Sidebar

### Struktur

```html
<nav class="flex-1 overflow-y-auto py-4 px-2 space-y-0.5">

  {{-- Item tunggal --}}
  <a href="{{ route('dashboard') }}"
     class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}
            flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors">
    <x-heroicon-o-home class="w-5 h-5 shrink-0" />
    <span class="sidebar-label truncate">Dashboard</span>
  </a>

  {{-- Separator dengan label --}}
  <div class="pt-4 pb-1 px-3">
    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-600 sidebar-label">
      Operasional
    </p>
  </div>

  {{-- Item dengan sub-menu (collapsible) --}}
  <div x-data="{ open: {{ request()->is('master*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg
                   {{ request()->is('master*') ? 'text-slate-900 dark:text-slate-50 bg-slate-100 dark:bg-slate-800' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}
                   transition-colors">
      <x-heroicon-o-circle-stack class="w-5 h-5 shrink-0" />
      <span class="sidebar-label flex-1 text-left truncate">Master Data</span>
      <svg class="w-4 h-4 shrink-0 sidebar-label transition-transform"
           :class="open ? 'rotate-90' : ''"
           fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
      </svg>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mt-0.5 ml-4 pl-4 border-l border-slate-200 dark:border-slate-700 space-y-0.5 sidebar-label">
      <a href="{{ route('master.produk.index') }}"
         class="{{ request()->routeIs('master.produk.*') ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/50' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                flex items-center gap-2.5 px-2.5 py-1.5 text-sm rounded-md transition-colors">
        Produk
      </a>
      <a href="{{ route('master.bahan-baku.index') }}"
         class="{{ request()->routeIs('master.bahan-baku.*') ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/50' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                flex items-center gap-2.5 px-2.5 py-1.5 text-sm rounded-md transition-colors">
        Bahan Baku
      </a>
      <a href="{{ route('master.pelanggan.index') }}"
         class="{{ request()->routeIs('master.pelanggan.*') ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/50' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                flex items-center gap-2.5 px-2.5 py-1.5 text-sm rounded-md transition-colors">
        Pelanggan
      </a>
      <a href="{{ route('master.supplier.index') }}"
         class="{{ request()->routeIs('master.supplier.*') ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/50' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                flex items-center gap-2.5 px-2.5 py-1.5 text-sm rounded-md transition-colors">
        Supplier
      </a>
    </div>
  </div>

</nav>
```

### Daftar Lengkap Navigasi

| Menu | Route Prefix | Heroicon | Sub-item |
|---|---|---|---|
| Dashboard | `dashboard` | `home` | — |
| Master Data | `master.*` | `circle-stack` | Produk, Bahan Baku, Pelanggan, Supplier |
| Penjualan | `penjualan.*` | `shopping-cart` | — |
| Produksi | `produksi.*` | `beaker` | — |
| Pembelian | `pembelian.*` | `truck` | — |
| Persediaan | `persediaan.*` | `archive-box` | — |
| SDM & Penggajian | `sdm.*` | `users` | — |
| Laporan Keuangan | `laporan.*` | `chart-bar` | — |
| Pengaturan | `pengaturan.*` | `cog-6-tooth` | — |

### Sidebar Collapsed State

Saat sidebar dalam mode collapse (kelas `.sidebar-collapsed` pada `#app-shell`), semua elemen dengan kelas `.sidebar-label` di-hide via:

```css
/* resources/css/app.css */
#app-shell.sidebar-collapsed #sidebar {
  width: 64px; /* w-16 */
}
#app-shell.sidebar-collapsed .sidebar-label {
  display: none;
}
```

```javascript
function toggleSidebarCollapse() {
  document.getElementById('app-shell').classList.toggle('sidebar-collapsed');
  localStorage.setItem('sidebar-collapsed',
    document.getElementById('app-shell').classList.contains('sidebar-collapsed'));
}
// Init
if (localStorage.getItem('sidebar-collapsed') === 'true') {
  document.getElementById('app-shell').classList.add('sidebar-collapsed');
}
```

---

## 8. Aturan UX

### 8.1 Konfirmasi Destruktif

```
WAJIB: Setiap aksi hapus (DELETE) harus melalui Modal konfirmasi (lihat 5g).
WAJIB: Tombol konfirmasi di modal hapus berwarna Danger (merah).
WAJIB: Sebutkan nama/identitas data yang akan dihapus di body modal.
DILARANG: Langsung menghapus tanpa konfirmasi, bahkan via AJAX.
```

**Implementasi di controller:**
```php
// Tidak ada redirect langsung — selalu tampilkan konfirmasi via modal di view
// Di table row: tombol hapus membuka modal, bukan langsung submit form
```

### 8.2 Loading State pada Async Operation

```html
<!-- Pola Alpine.js untuk loading state -->
<div x-data="{ loading: false }">
  <form @submit.prevent="loading = true; $el.submit()" method="POST">
    @csrf
    <!-- ... fields ... -->
    <button type="submit" :disabled="loading"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
                   bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm
                   disabled:opacity-70 disabled:cursor-not-allowed transition-all">
      <template x-if="loading">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
      </template>
      <template x-if="!loading">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
      </template>
      <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
    </button>
  </form>
</div>
```

### 8.3 Flash Message via Toast

```php
// Di semua controller — gunakan session flash, bukan redirect dengan query param
return redirect()->route('products.index')
    ->with('success', 'Produk berhasil ditambahkan.');

return redirect()->back()
    ->with('error', 'Terjadi kesalahan saat menyimpan data.');

return redirect()->back()
    ->with('warning', 'Stok hampir habis. Periksa persediaan Anda.');
```

Sistem Toast di layout akan otomatis membaca session ini dan menampilkan notifikasi (lihat 5h).

### 8.4 Paginasi Wajib

```
WAJIB: Setiap query yang berpotensi menghasilkan > 50 baris wajib dipaginasi.
Gunakan: Model::paginate(15) atau paginate(25) — jangan paginate() tanpa argumen.
Tampilkan komponen pagination (lihat 5i) di footer tabel.
Tampilkan info "Menampilkan X–Y dari Z data" di footer tabel.
```

```php
// Di Controller
public function index()
{
    $products = Product::query()
        ->when(request('search'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
        ->latest()
        ->paginate(15)
        ->withQueryString(); // Pertahankan parameter search saat paginasi

    return view('products.index', compact('products'));
}
```

### 8.5 Responsive & Sidebar Collapse

```
✅ Sidebar otomatis TERSEMBUNYI di layar < 1024px (lg)
✅ Tombol hamburger muncul di topbar mobile
✅ Backdrop gelap muncul saat sidebar terbuka di mobile
✅ Menutup sidebar saat klik backdrop atau navigasi ke halaman baru
✅ State collapse sidebar disimpan di localStorage
```

### 8.6 Aturan Tambahan

| Aturan | Keterangan |
|---|---|
| **Focus Trap** | Modal yang terbuka harus menjebak fokus keyboard di dalamnya. |
| **ARIA Labels** | Semua tombol ikon-only wajib punya `aria-label`. |
| **Form Validation** | Tampilkan error per-field di bawah input (warna merah, ikon ⚠). |
| **Angka Finansial** | Selalu gunakan `font-mono` dan format `Rp X.XXX.XXX`. |
| **Tanggal** | Format tampilan: `22 Mei 2026`. Format input: datepicker atau `YYYY-MM-DD`. |
| **Konfirmasi Keluar** | Tampilkan dialog "Ada perubahan yang belum disimpan" saat meninggalkan form yang sudah dimodifikasi. |
| **Zero State** | Setiap list/tabel wajib memiliki Empty State (lihat 5l). |
| **Error State** | Halaman error (404, 500) harus berdesain sesuai sistem ini, bukan bawaan Laravel. |

---

## Appendix: Dependensi Wajib

```json
// package.json
{
  "devDependencies": {
    "tailwindcss": "^3.x",
    "autoprefixer": "^10.x",
    "postcss": "^8.x"
  }
}
```

```json
// composer.json (tambahan)
{
  "require": {
    "blade-ui-kit/blade-heroicons": "^2.x"
  }
}
```

```html
<!-- Di layout utama <head> -->
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<!-- Alpine.js (untuk interaktivitas: dropdown, modal, tab, toggle) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

---

*Dokumen ini dikelola oleh tim developer. Setiap perubahan desain harus diperbarui di sini sebelum diimplementasikan ke kode.*
