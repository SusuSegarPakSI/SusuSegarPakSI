<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\ProduksiBiaya;
use App\Models\ProduksiBahanPakai;
use App\Models\BillOfMaterial;
use App\Models\Produk;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the production runs and statistics.
     */
    public function index(Request $request)
    {
        $query = Produksi::with(['produkOutput', 'creator']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_ke')) {
            $query->whereDate('tanggal_mulai', '<=', $request->tanggal_ke);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_produksi', 'like', "%{$search}%")
                  ->orWhere('nama_batch', 'like', "%{$search}%")
                  ->orWhereHas('produkOutput', function($pq) use ($search) {
                      $pq->where('nama_produk', 'like', "%{$search}%");
                  });
            });
        }

        $produksis = $query->latest('tanggal_mulai')->paginate(10)->withQueryString();

        // ══════ CALCULATE KPI SUMMARY CARDS ══════
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        // 1. Batch Aktif Saat Ini (status Proses)
        $batchAktif = Produksi::where('status', 'Proses')->count();

        // 2. Total Batch Bulan Ini (Selesai in current month)
        $batchBulanIni = Produksi::where('status', 'Selesai')
            ->whereBetween('tanggal_selesai', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // 3. Rata-rata HPP Bulan Ini
        $rataHppBulanIni = Produksi::where('status', 'Selesai')
            ->whereBetween('tanggal_selesai', [$currentMonthStart, $currentMonthEnd])
            ->whereNotNull('hpp_per_unit')
            ->avg('hpp_per_unit') ?? 0;

        return view('produksi.index', compact('produksis', 'batchAktif', 'batchBulanIni', 'rataHppBulanIni'));
    }

    /**
     * Show the detailed production run.
     */
    public function show($id)
    {
        $produksi = Produksi::with(['produkOutput', 'creator', 'biayas', 'bahanPakais.bahanBaku'])->findOrFail($id);
        return view('produksi.show', compact('produksi'));
    }

    /**
     * Show the form for creating a new production run.
     */
    public function create()
    {
        $produks = Produk::where('is_active', true)->with('bom.bahanBaku')->get();
        $bahanBakus = BahanBaku::where('is_active', true)->get();

        return view('produksi.create', compact('produks', 'bahanBakus'));
    }

    /**
     * Store a newly created production run in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_batch' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'produk_output_id' => 'required|exists:produks,id',
            'jumlah_batch' => 'required|integer|min:1',
            'target_output' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'status' => 'required|in:Draft,Proses',
            // Cost inputs from Step 2
            'bahan_baku' => 'required|array', // key: bahan_baku_id => cost value
            'bahan_baku_kuantitas' => 'required|array', // key: bahan_baku_id => used quantity
            'bahan_penolong' => 'nullable|array', // array of ['keterangan' => X, 'nominal' => Y]
            'tenaga_kerja_nominal' => 'required|numeric|min:0',
            'overhead' => 'nullable|array', // array of ['keterangan' => X, 'nominal' => Y]
        ]);

        // ══════ BOM STOCK VALIDATION ══════
        $bomItems = BillOfMaterial::where('produk_id', $validated['produk_output_id'])->get();
        if ($bomItems->isEmpty()) {
            return back()->withErrors(['produk_output_id' => 'Resep BOM untuk produk ini belum dikonfigurasi. Silakan isi resep terlebih dahulu.'])->withInput();
        }

        // Validate stock before decrementing
        foreach ($bomItems as $item) {
            $requiredQty = $item->kuantitas_per_batch * $validated['jumlah_batch'];
            $bahanBaku = BahanBaku::find($item->bahan_baku_id);
            if (!$bahanBaku || $bahanBaku->stok < $requiredQty) {
                $namaBahan = $bahanBaku ? $bahanBaku->nama_bahan : 'Tidak Dikenal';
                $stokTersedia = $bahanBaku ? $bahanBaku->stok : 0;
                $satuan = $bahanBaku ? $bahanBaku->satuan : $item->satuan;
                
                return back()->withErrors([
                    'stok' => "Stok bahan baku {$namaBahan} tidak mencukupi. Dibutuhkan " . number_format($requiredQty, 2, ',', '.') . " {$satuan} namun hanya tersedia " . number_format($stokTersedia, 2, ',', '.') . " {$satuan}."
                ])->withInput();
            }
        }

        // ══════ DATABASE TRANSACTION ══════
        DB::transaction(function () use ($validated, $bomItems, $request) {
            $nomorProduksi = Produksi::generateNomorProduksi($validated['tanggal_mulai']);

            $produksi = Produksi::create([
                'nomor_produksi' => $nomorProduksi,
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'nama_batch' => $validated['nama_batch'],
                'produk_output_id' => $validated['produk_output_id'],
                'jumlah_batch' => $validated['jumlah_batch'],
                'target_output' => $validated['target_output'],
                'status' => $validated['status'],
                'catatan' => $request->input('catatan'),
                'created_by' => Auth::id(),
            ]);

            // Save Raw Materials Consumption & Costs
            foreach ($bomItems as $item) {
                $bahanBaku = BahanBaku::findOrFail($item->bahan_baku_id);
                $qtyPakai = $request->input("bahan_baku_kuantitas.{$item->bahan_baku_id}", $item->kuantitas_per_batch * $validated['jumlah_batch']);
                $nominalCost = $request->input("bahan_baku.{$item->bahan_baku_id}", $qtyPakai * $bahanBaku->harga_rata_rata);

                // Save snapshot usage
                ProduksiBahanPakai::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $item->bahan_baku_id,
                    'kuantitas_pakai' => $qtyPakai,
                    'harga_satuan_saat_itu' => $bahanBaku->harga_rata_rata,
                ]);

                // Decrement stock
                $bahanBaku->decrement('stok', $qtyPakai);

                // Save cost entry
                ProduksiBiaya::create([
                    'produksi_id' => $produksi->id,
                    'jenis_biaya' => 'bahan_baku',
                    'keterangan' => 'Bahan Baku: ' . $bahanBaku->nama_bahan,
                    'nominal' => $nominalCost,
                ]);
            }

            // Save Auxiliary Materials
            $bahanPenolong = $request->input('bahan_penolong', []);
            if (!empty($bahanPenolong)) {
                foreach ($bahanPenolong as $row) {
                    if (!empty($row['keterangan']) && isset($row['nominal'])) {
                        ProduksiBiaya::create([
                            'produksi_id' => $produksi->id,
                            'jenis_biaya' => 'bahan_penolong',
                            'keterangan' => $row['keterangan'],
                            'nominal' => $row['nominal'],
                        ]);
                    }
                }
            }

            // Save Labor cost
            ProduksiBiaya::create([
                'produksi_id' => $produksi->id,
                'jenis_biaya' => 'tenaga_kerja',
                'keterangan' => 'Biaya Tenaga Kerja Langsung',
                'nominal' => $validated['tenaga_kerja_nominal'],
            ]);

            // Save Overhead
            $overhead = $request->input('overhead', []);
            if (!empty($overhead)) {
                foreach ($overhead as $row) {
                    if (!empty($row['keterangan']) && isset($row['nominal'])) {
                        ProduksiBiaya::create([
                            'produksi_id' => $produksi->id,
                            'jenis_biaya' => 'overhead',
                            'keterangan' => $row['keterangan'],
                            'nominal' => $row['nominal'],
                        ]);
                    }
                }
            }
        });

        $msg = $validated['status'] === 'Proses' 
            ? 'Produksi berhasil dimulai (status: Proses).' 
            : 'Draft produksi berhasil disimpan.';

        return redirect()->route('produksi.index')->with('success', $msg);
    }

    /**
     * Start a draft production batch.
     */
    public function mulai($id)
    {
        $produksi = Produksi::findOrFail($id);
        if ($produksi->status !== 'Draft') {
            return back()->withErrors(['error' => 'Hanya batch dengan status Draft yang dapat dimulai.']);
        }

        $produksi->update([
            'status' => 'Proses',
        ]);

        return redirect()->route('produksi.show', $produksi)->with('success', 'Batch produksi berhasil dimulai (status: Proses).');
    }

    /**
     * Mark a production batch as completed and calculate HPP.
     */
    public function selesaikan(Request $request, $id)
    {
        $validated = $request->validate([
            'actual_output' => 'required|integer|min:1',
        ]);

        $produksi = Produksi::findOrFail($id);
        if ($produksi->status !== 'Proses') {
            return back()->withErrors(['error' => 'Hanya batch dengan status Proses yang dapat diselesaikan.']);
        }

        DB::transaction(function () use ($produksi, $validated) {
            $totalBiaya = ProduksiBiaya::where('produksi_id', $produksi->id)->sum('nominal');
            $hppPerUnit = $totalBiaya / $validated['actual_output'];

            $produksi->update([
                'actual_output' => $validated['actual_output'],
                'hpp_per_unit' => $hppPerUnit,
                'status' => 'Selesai',
                'tanggal_selesai' => now(),
            ]);

            // Update produk.harga_pokok dengan Moving Average 3 batch terakhir
            $last3Hpps = Produksi::where('produk_output_id', $produksi->produk_output_id)
                ->where('status', 'Selesai')
                ->whereNotNull('hpp_per_unit')
                ->latest('tanggal_selesai')
                ->latest('id')
                ->take(3)
                ->pluck('hpp_per_unit');

            $movingAverageHpp = $last3Hpps->avg();

            // Increment produk stock and update HPP price
            $produk = Produk::findOrFail($produksi->produk_output_id);
            $produk->update([
                'harga_pokok' => $movingAverageHpp,
                'stok' => $produk->stok + $validated['actual_output'],
            ]);
        });

        return redirect()->route('produksi.show', $produksi)->with('success', 'Batch produksi berhasil diselesaikan. HPP per unit dihitung dan stok produk bertambah.');
    }

    /**
     * Cancel a production batch and restore raw materials.
     */
    public function batalkan($id)
    {
        $produksi = Produksi::findOrFail($id);
        if (!in_array($produksi->status, ['Draft', 'Proses'])) {
            return back()->withErrors(['error' => 'Hanya batch dengan status Draft atau Proses yang dapat dibatalkan.']);
        }

        DB::transaction(function () use ($produksi) {
            // Restore raw materials
            $bahanPakais = ProduksiBahanPakai::where('produksi_id', $produksi->id)->get();
            foreach ($bahanPakais as $pakai) {
                $bahanBaku = BahanBaku::find($pakai->bahan_baku_id);
                if ($bahanBaku) {
                    $bahanBaku->increment('stok', $pakai->kuantitas_pakai);
                }
            }

            $produksi->update([
                'status' => 'Dibatalkan',
            ]);
        });

        return redirect()->route('produksi.show', $produksi)->with('success', 'Batch produksi berhasil dibatalkan dan seluruh stok bahan baku telah dikembalikan.');
    }

    /**
     * BOM Recipes List (Admin Only).
     */
    public function bomIndex()
    {
        $produks = Produk::with('bom.bahanBaku')->paginate(10);
        $bahanBakus = BahanBaku::where('is_active', true)->get();

        return view('bom.index', compact('produks', 'bahanBakus'));
    }

    /**
     * Update/Configure BOM Recipes (Admin Only).
     */
    public function bomUpdate(Request $request, $produkId)
    {
        $request->validate([
            'bom' => 'nullable|array',
            'bom.*.bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'bom.*.kuantitas_per_batch' => 'required|numeric|min:0.01',
            'bom.*.satuan' => 'required|string|max:50',
        ]);

        $produk = Produk::findOrFail($produkId);

        DB::transaction(function () use ($produk, $request) {
            // Clear current BOM entries
            BillOfMaterial::where('produk_id', $produk->id)->delete();

            // Insert new ones
            if ($request->has('bom')) {
                foreach ($request->input('bom') as $item) {
                    if (!empty($item['bahan_baku_id']) && !empty($item['kuantitas_per_batch'])) {
                        BillOfMaterial::create([
                            'produk_id' => $produk->id,
                            'bahan_baku_id' => $item['bahan_baku_id'],
                            'kuantitas_per_batch' => $item['kuantitas_per_batch'],
                            'satuan' => $item['satuan'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('bom.index')->with('success', "Resep BOM untuk produk {$produk->nama_produk} berhasil diperbarui.");
    }
}
