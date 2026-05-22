<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\PenjualanRetur;
use App\Models\PenjualanReturDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Display a listing of sales history with stats and filters.
     */
    public function index(Request $request)
    {
        $query = Penjualan::with(['customer', 'creator']);

        // Filter: Range Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter: Metode Bayar
        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->metode_bayar);
        }

        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penjualans = $query->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        // ── Summary Cards (Today) ──
        $today = date('Y-m-d');
        $todayQuery = Penjualan::whereDate('tanggal', $today);

        $jumlahTransaksi = (clone $todayQuery)->count();
        $omzet = (clone $todayQuery)->sum('grand_total');
        $rataRata = $jumlahTransaksi > 0 ? $omzet / $jumlahTransaksi : 0;

        return view('penjualan.index', compact(
            'penjualans',
            'jumlahTransaksi',
            'omzet',
            'rataRata'
        ));
    }

    /**
     * Show the checkout point of sale screen.
     */
    public function create()
    {
        // Load active products with positive stock or is_active
        $products = Produk::where('is_active', true)->orderBy('nama_produk')->get();
        // Load active customers
        $customers = Customer::where('is_active', true)->orderBy('nama')->get();

        return view('penjualan.create', compact('products', 'customers'));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'diskon_nominal' => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:Tunai,Transfer,QRIS',
            'jumlah_bayar' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.kuantitas' => 'required|integer|min:1',
        ]);

        try {
            $penjualan = DB::transaction(function () use ($request) {
                $tanggal = $request->tanggal;
                $nomorTransaksi = Penjualan::generateNomorTransaksi($tanggal);

                $subtotal = 0;
                $detailsData = [];

                foreach ($request->items as $item) {
                    $produk = Produk::lockForUpdate()->find($item['produk_id']);

                    if (!$produk->is_active) {
                        throw new \Exception("Produk {$produk->nama_produk} tidak aktif.");
                    }

                    if ($produk->stok < $item['kuantitas']) {
                        throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi. (Tersedia: {$produk->stok}, Diminta: {$item['kuantitas']})");
                    }

                    // Decrement stock
                    $produk->decrement('stok', $item['kuantitas']);

                    $itemSubtotal = $item['kuantitas'] * $produk->harga_jual;
                    $subtotal += $itemSubtotal;

                    $detailsData[] = new PenjualanDetail([
                        'produk_id' => $produk->id,
                        'harga_satuan' => $produk->harga_jual,
                        'hpp_satuan' => $produk->harga_pokok,
                        'kuantitas' => $item['kuantitas'],
                        'subtotal' => $itemSubtotal,
                    ]);
                }

                $diskon = $request->diskon_nominal;
                $grandTotal = max(0, $subtotal - $diskon);
                $kembalian = 0;

                if ($request->metode_bayar === 'Tunai') {
                    if ($request->jumlah_bayar < $grandTotal) {
                        throw new \Exception("Jumlah bayar kurang dari total transaksi.");
                    }
                    $kembalian = $request->jumlah_bayar - $grandTotal;
                } else {
                    $request->merge(['jumlah_bayar' => $grandTotal]);
                }

                $penjualan = Penjualan::create([
                    'nomor_transaksi' => $nomorTransaksi,
                    'tanggal' => $tanggal,
                    'customer_id' => $request->customer_id,
                    'subtotal' => $subtotal,
                    'diskon_nominal' => $diskon,
                    'grand_total' => $grandTotal,
                    'metode_bayar' => $request->metode_bayar,
                    'jumlah_bayar' => $request->metode_bayar === 'Tunai' ? $request->jumlah_bayar : $grandTotal,
                    'kembalian' => $kembalian,
                    'status' => 'Lunas',
                    'catatan' => $request->catatan,
                    'created_by' => auth()->id(),
                ]);

                $penjualan->details()->saveMany($detailsData);

                return $penjualan;
            });

            return redirect()->route('penjualan.index')->with('success', "Transaksi {$penjualan->nomor_transaksi} berhasil diproses.");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Show a detailed invoice transaction.
     */
    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['customer', 'creator', 'details.produk', 'returs.details.produk']);

        // Calculate already returned quantities per product
        $alreadyReturned = DB::table('penjualan_retur_details')
            ->join('penjualan_returs', 'penjualan_retur_details.retur_id', '=', 'penjualan_returs.id')
            ->where('penjualan_returs.penjualan_id', $penjualan->id)
            ->groupBy('penjualan_retur_details.produk_id')
            ->select('penjualan_retur_details.produk_id', DB::raw('SUM(penjualan_retur_details.kuantitas_retur) as total_returned'))
            ->pluck('total_returned', 'produk_id')
            ->toArray();

        return view('penjualan.show', compact('penjualan', 'alreadyReturned'));
    }

    /**
     * Process sales retur.
     */
    public function retur(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'tanggal_retur' => 'required|date',
            'alasan' => 'required|string',
            'kembalikan_stok' => 'nullable|boolean',
            'items' => 'required|array', // keyed by produk_id
        ]);

        if ($penjualan->status === 'Retur Penuh') {
            return back()->withErrors('Transaksi ini sudah diretur penuh.');
        }

        $kembalikanStok = $request->boolean('kembalikan_stok', false);

        try {
            DB::transaction(function () use ($request, $penjualan, $kembalikanStok) {
                $retur = PenjualanRetur::create([
                    'penjualan_id' => $penjualan->id,
                    'tanggal_retur' => $request->tanggal_retur,
                    'alasan' => $request->alasan,
                    'total_nilai_retur' => 0,
                    'kembalikan_stok' => $kembalikanStok,
                ]);

                $totalNilaiRetur = 0;
                $anyItemsReturned = false;

                foreach ($request->items as $produkId => $qty) {
                    $qty = (int)$qty;
                    if ($qty <= 0) continue;

                    $detail = $penjualan->details()->where('produk_id', $produkId)->first();
                    if (!$detail) continue;

                    // Calculate already returned qty for this product
                    $alreadyReturned = DB::table('penjualan_retur_details')
                        ->join('penjualan_returs', 'penjualan_retur_details.retur_id', '=', 'penjualan_returs.id')
                        ->where('penjualan_returs.penjualan_id', $penjualan->id)
                        ->where('penjualan_retur_details.produk_id', $produkId)
                        ->sum('penjualan_retur_details.kuantitas_retur');

                    $maxReturQty = $detail->kuantitas - $alreadyReturned;
                    if ($qty > $maxReturQty) {
                        throw new \Exception("Kuantitas retur untuk produk {$detail->produk->nama_produk} melebihi kuantitas yang dibeli.");
                    }

                    $subtotal = $qty * $detail->harga_satuan;

                    PenjualanReturDetail::create([
                        'retur_id' => $retur->id,
                        'produk_id' => $produkId,
                        'kuantitas_retur' => $qty,
                        'harga_satuan' => $detail->harga_satuan,
                        'subtotal' => $subtotal,
                    ]);

                    $totalNilaiRetur += $subtotal;
                    $anyItemsReturned = true;

                    if ($kembalikanStok) {
                        $produk = $detail->produk;
                        $produk->increment('stok', $qty);
                    }
                }

                if (!$anyItemsReturned) {
                    throw new \Exception("Minimal harus ada 1 barang dengan jumlah retur valid yang diproses.");
                }

                $retur->update(['total_nilai_retur' => $totalNilaiRetur]);

                // Update parent status
                $totalItemsBought = $penjualan->details()->sum('kuantitas');
                $totalItemsReturned = DB::table('penjualan_retur_details')
                    ->join('penjualan_returs', 'penjualan_retur_details.retur_id', '=', 'penjualan_returs.id')
                    ->where('penjualan_returs.penjualan_id', $penjualan->id)
                    ->sum('penjualan_retur_details.kuantitas_retur');

                if ($totalItemsReturned >= $totalItemsBought) {
                    $penjualan->update(['status' => 'Retur Penuh']);
                } else {
                    $penjualan->update(['status' => 'Retur Sebagian']);
                }
            });

            return redirect()->route('penjualan.show', $penjualan->id)->with('success', 'Retur penjualan berhasil diproses.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Print thermal receipt layout without navbar/sidebar shell.
     */
    public function cetakStruk(Penjualan $penjualan)
    {
        $penjualan->load(['customer', 'creator', 'details.produk']);
        return view('penjualan.struk', compact('penjualan'));
    }
}
