<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk POS - {{ $penjualan->nomor_transaksi }}</title>

    <!-- Tailwind CSS (via Vite or Fallback CDN to ensure it works anywhere) -->
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Custom print stylesheet overrides */
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Thermal receipt styling for screen preview */
        @media screen {
            body {
                background-color: #0f172a; /* Slate 900 */
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                min-height: 100vh;
                padding: 80px 20px 40px;
                color: #e2e8f0;
            }
            .receipt-container {
                width: 80mm;
                background: #ffffff;
                color: #000000;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                padding: 18px;
                border-radius: 4px;
                position: relative;
            }
            /* Jagged top/bottom edges for a premium thermal scroll aesthetic */
            .receipt-container::before {
                content: "";
                position: absolute;
                top: -10px;
                left: 0;
                right: 0;
                height: 10px;
                background-image: linear-gradient(135deg, #ffffff 4px, transparent 0), linear-gradient(225deg, #ffffff 4px, transparent 0);
                background-position: left top;
                background-repeat: repeat-x;
                background-size: 8px 10px;
            }
            .receipt-container::after {
                content: "";
                position: absolute;
                bottom: -10px;
                left: 0;
                right: 0;
                height: 10px;
                background-image: linear-gradient(45deg, #ffffff 4px, transparent 0), linear-gradient(315deg, #ffffff 4px, transparent 0);
                background-position: left bottom;
                background-repeat: repeat-x;
                background-size: 8px 10px;
            }
        }

        /* Thermal receipt styling for physical printing */
        @media print {
            /* Hide UI components completely */
            .no-print {
                display: none !important;
            }
            /* Reset body styles */
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 80mm !important;
            }
            .receipt-container {
                width: 80mm !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                border: none !important;
            }
            .receipt-container::before, .receipt-container::after {
                display: none !important;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Top floating action bar (hidden on print) -->
    <div class="no-print fixed top-0 left-0 right-0 h-16 bg-slate-900/90 backdrop-blur border-b border-slate-800 flex items-center justify-between px-6 z-50 shadow-md">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-sm font-semibold text-slate-100 tracking-tight">Pratinjau Struk Penjualan</span>
            <span class="text-xs font-mono bg-slate-800 border border-slate-700 text-slate-400 px-2 py-0.5 rounded">{{ $penjualan->nomor_transaksi }}</span>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.618 0-1.113-.487-1.12-1.106L6 18m11.66 0H6.34m9.68-14.333H7.98m7.7 0a48.536 48.536 0 0 1 2.3 2.288c.086.092.107.223.053.336a10.024 10.024 0 0 1-.95 1.583m-10.8 0a10.025 10.025 0 0 1-.95-1.583.344.344 0 0 1 .054-.336 48.574 48.574 0 0 1 2.3-2.288m7.7 0H7.98m0 0L6.72 8.357m1.26-4.5H16.02L17.28 8.357M7.98 3.827H16.02m-.02 4.53H8M17.66 18v-3.07a9.07 9.07 0 0 0-1.424-4.887l-.02-.03a9.07 9.07 0 0 0-6.432-3.83" />
                </svg>
                Cetak Struk
            </button>
            <button onclick="window.close()" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition-all focus:outline-none">
                Tutup Halaman
            </button>
        </div>
    </div>

    <!-- Virtual/Physical Thermal Scroll Container -->
    <div class="receipt-container text-[11px] leading-relaxed text-slate-900">
        
        <!-- Header / Store Info -->
        <div class="text-center mb-4">
            <h1 class="text-base font-bold tracking-tight text-slate-900 uppercase">Susu Segar Pak SI</h1>
            <p class="text-[9px] text-slate-500 font-medium">Murni, Segar, Menyehatkan</p>
            <p class="text-[9px] text-slate-500 mt-1">Jl. Kaliurang KM 12.5, Sleman, Yogyakarta</p>
            <p class="text-[9px] text-slate-500">Telp: 0812-3456-7890</p>
        </div>

        <!-- Receipt Metadata -->
        <div class="border-t border-b border-dashed border-slate-300 py-2.5 my-2.5 text-[9px] space-y-1 font-medium text-slate-600">
            <div class="flex justify-between">
                <span>No. Nota:</span>
                <span class="font-mono text-slate-900 font-semibold">{{ $penjualan->nomor_transaksi }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tanggal:</span>
                <span class="font-mono text-slate-900">{{ $penjualan->tanggal->format('d/m/Y') }} {{ date('H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Operator:</span>
                <span class="text-slate-900 uppercase">{{ $penjualan->creator ? $penjualan->creator->name : '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pelanggan:</span>
                <span class="text-slate-900 font-semibold">{{ $penjualan->customer ? $penjualan->customer->nama : 'Pelanggan Umum' }}</span>
            </div>
            @if($penjualan->status !== 'Lunas')
            <div class="flex justify-between">
                <span>Status Transaksi:</span>
                <span class="px-1 text-[8px] font-bold rounded uppercase bg-red-100 text-red-800">{{ $penjualan->status }}</span>
            </div>
            @endif
        </div>

        <!-- Purchased Items Section -->
        <div class="space-y-3">
            @foreach($penjualan->details as $detail)
            <div class="space-y-0.5">
                <!-- Row 1: Product Name -->
                <div class="font-semibold text-slate-900 uppercase text-[10px] leading-tight">
                    {{ $detail->produk->nama_produk }}
                </div>
                <!-- Row 2: Calculation Breakdown & Total -->
                <div class="flex justify-between items-center text-slate-600 font-mono text-[10px]">
                    <div>
                        {{ $detail->kuantitas }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                    </div>
                    <div class="font-semibold text-slate-900">
                        {{ number_format($detail->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Financial Breakdown -->
        <div class="border-t border-dashed border-slate-300 mt-4 pt-3.5 space-y-1.5 font-medium text-[10px]">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal Belanja</span>
                <span class="font-mono">{{ number_format($penjualan->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($penjualan->diskon_nominal > 0)
            <div class="flex justify-between text-red-600">
                <span>Diskon POS</span>
                <span class="font-mono">-{{ number_format($penjualan->diskon_nominal, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-slate-900 font-bold border-t border-dotted border-slate-200 pt-1.5 text-[11px]">
                <span>TOTAL AKHIR</span>
                <span class="font-mono text-base">{{ number_format($penjualan->grand_total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-500 text-[9px] pt-1">
                <span>Metode Pembayaran</span>
                <span class="uppercase font-semibold text-slate-800">{{ $penjualan->metode_bayar }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Jumlah Dibayar</span>
                <span class="font-mono text-slate-900">{{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Kembalian</span>
                <span class="font-mono text-slate-900">{{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Notes (If any) -->
        @if($penjualan->catatan)
        <div class="border-t border-dotted border-slate-200 mt-3 pt-2 text-[9px] text-slate-500 italic text-center">
            * Catatan: "{{ $penjualan->catatan }}"
        </div>
        @endif

        <!-- Footer / Greetings -->
        <div class="border-t border-dashed border-slate-300 mt-4 pt-4 text-center space-y-1 text-[9px] text-slate-500">
            <p class="font-semibold text-slate-700">Terima Kasih Atas Kunjungan Anda</p>
            <p>Susu Segar Murni Berkualitas Tinggi</p>
            <p>Untuk Tubuh Bugar & Jiwa Cerdas</p>
            <div class="pt-2 text-[8px] text-slate-400">
                * Simpan struk ini sebagai bukti pembayaran sah *
            </div>
        </div>

    </div>

    <!-- Automatic print trigger on view load -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Avoid printing automatically when embedded in frames/tools, but trigger for regular page loading after 500ms
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
