<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\PembayaranHutang;
use App\Models\BahanBaku;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembelianController extends Controller
{
    /**
     * Display a listing of the purchases.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'semua');

        $query = Pembelian::with('supplier');

        if ($tab === 'belum_lunas') {
            $query->where('status_bayar', 'Belum Lunas');
        }

        $pembelians = $query->orderBy('tanggal_faktur', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Calculate KPI values
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Total Pembelian Bulan Ini
        $totalBulanIni = Pembelian::whereBetween('tanggal_faktur', [$startOfMonth, $endOfMonth])
            ->sum('total');

        // Total Hutang Aktif
        $totalHutangAktif = Supplier::sum('saldo_hutang');

        return view('pembelian.index', compact('pembelians', 'tab', 'totalBulanIni', 'totalHutangAktif'));
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
        }

        $suppliers = Supplier::where('is_active', true)->orderBy('nama_supplier', 'asc')->get();
        $bahanBakus = BahanBaku::where('is_active', true)->orderBy('nama_bahan', 'asc')->get();

        return view('pembelian.create', compact('suppliers', 'bahanBakus'));
    }

    /**
     * Store a newly created purchase in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nomor_faktur'        => 'required|string|max:50|unique:pembelians,nomor_faktur',
            'nomor_po'            => 'nullable|string|max:50',
            'supplier_id'         => 'required|exists:suppliers,id',
            'tanggal_faktur'      => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|required_if:status_bayar,Belum Lunas|date|after_or_equal:tanggal_faktur',
            'status_bayar'        => 'required|in:Lunas,Belum Lunas',
            'items'               => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'items.*.kuantitas'   => 'required|integer|min:1',
            'items.*.harga_satuan'=> 'required|numeric|min:0',
            'catatan'             => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $itemSubtotal = $item['kuantitas'] * $item['harga_satuan'];
                $subtotal += $itemSubtotal;
                $itemsData[] = [
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'kuantitas'     => $item['kuantitas'],
                    'harga_satuan'  => $item['harga_satuan'],
                    'subtotal'      => $itemSubtotal,
                ];
            }

            // Create purchase header
            $pembelian = Pembelian::create([
                'nomor_faktur'        => $request->nomor_faktur,
                'nomor_po'            => $request->nomor_po,
                'supplier_id'         => $request->supplier_id,
                'tanggal_faktur'      => $request->tanggal_faktur,
                'tanggal_jatuh_tempo' => $request->status_bayar === 'Belum Lunas' ? $request->tanggal_jatuh_tempo : null,
                'subtotal'            => $subtotal,
                'total'               => $subtotal,
                'status_bayar'        => $request->status_bayar,
                'catatan'             => $request->catatan,
                'created_by'          => Auth::id(),
            ]);

            // Save details, update Bahan Baku stock and moving average price
            foreach ($itemsData as $data) {
                $pembelian->details()->create($data);

                // Fetch bahan baku with row lock
                $bahan = BahanBaku::lockForUpdate()->find($data['bahan_baku_id']);

                $stokLama = $bahan->stok;
                $hargaLama = $bahan->harga_rata_rata;

                $qtyBaru = $data['kuantitas'];
                $hargaBeli = $data['harga_satuan'];

                // Calculate Moving Average:
                // harga_baru = ((stok_lama * harga_lama) + (qty_baru * harga_beli)) / (stok_lama + qty_baru)
                $totalQty = $stokLama + $qtyBaru;
                if ($totalQty > 0) {
                    $hargaBaru = (($stokLama * $hargaLama) + ($qtyBaru * $hargaBeli)) / $totalQty;
                } else {
                    $hargaBaru = $hargaBeli;
                }

                // Update stock and avg price without changing kode_bahan at all
                $bahan->update([
                    'stok'             => $stokLama + $qtyBaru,
                    'harga_rata_rata'  => $hargaBaru,
                ]);
            }

            // If status_bayar is Belum Lunas, update supplier's saldo_hutang
            if ($request->status_bayar === 'Belum Lunas') {
                $supplier = Supplier::lockForUpdate()->find($request->supplier_id);
                $supplier->increment('saldo_hutang', $subtotal);
            }
        });

        return redirect()->route('pembelian.index')
                         ->with('success', 'Nota Pembelian #' . $request->nomor_faktur . ' berhasil disimpan dan stok telah diperbarui.');
    }

    /**
     * Display the specified purchase.
     */
    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'creator', 'details.bahanBaku', 'pembayaranHutangs'])->findOrFail($id);

        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Record a payment for outstanding debt.
     */
    public function bayarHutang(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $pembelian = Pembelian::findOrFail($id);

        if ($pembelian->status_bayar === 'Lunas') {
            return redirect()->back()->with('error', 'Nota Pembelian ini sudah lunas.');
        }

        $request->validate([
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar'  => 'required|numeric|min:1',
            'metode'        => 'required|string|max:50',
            'catatan'       => 'nullable|string',
        ]);

        // Calculate outstanding balance
        $totalTerbayar = $pembelian->pembayaranHutangs()->sum('jumlah_bayar');
        $sisaHutang = $pembelian->total - $totalTerbayar;

        if ($request->jumlah_bayar > $sisaHutang) {
            return redirect()->back()->with('error', 'Jumlah bayar (Rp ' . number_format($request->jumlah_bayar, 0, ',', '.') . ') melebihi sisa hutang (Rp ' . number_format($sisaHutang, 0, ',', '.') . ').');
        }

        DB::transaction(function () use ($pembelian, $request, $sisaHutang) {
            // Record payment
            PembayaranHutang::create([
                'pembelian_id'  => $pembelian->id,
                'tanggal_bayar' => $request->tanggal_bayar,
                'jumlah_bayar'  => $request->jumlah_bayar,
                'metode'        => $request->metode,
                'catatan'       => $request->catatan,
            ]);

            // Deduct supplier debt
            $supplier = Supplier::lockForUpdate()->find($pembelian->supplier_id);
            $supplier->decrement('saldo_hutang', $request->jumlah_bayar);

            // Update status_bayar if fully paid
            if (abs($request->jumlah_bayar - $sisaHutang) < 0.01) {
                $pembelian->update([
                    'status_bayar'        => 'Lunas',
                    'tanggal_jatuh_tempo' => null, // Reset or keep as is, but setting is standard
                ]);
            }
        });

        return redirect()->route('pembelian.show', $pembelian->id)
                         ->with('success', 'Pembayaran hutang berhasil dicatat.');
    }
}
