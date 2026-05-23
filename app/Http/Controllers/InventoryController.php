<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\StockAdjustment;
use App\Models\PembelianDetail;
use App\Models\PenjualanDetail;
use App\Models\PenjualanReturDetail;
use App\Models\Produksi;
use App\Models\ProduksiBahanPakai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'produk');

        $produks = Produk::where('is_active', true)->orderBy('nama_produk', 'asc')->get();
        $bahanBakus = BahanBaku::with('supplierUtama')->where('is_active', true)->orderBy('nama_bahan', 'asc')->get();
        $adjustments = StockAdjustment::with('creator')->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        // Calculate Asset Values
        $nilaiAsetProduk = 0;
        foreach ($produks as $p) {
            $p->nilai_aset = $p->stok * $p->harga_pokok;
            $nilaiAsetProduk += $p->nilai_aset;
            
            // Set Status
            $p->status_stok = $this->calculateStockStatus($p->stok, $p->stok_minimum);
        }

        $nilaiAsetBahan = 0;
        foreach ($bahanBakus as $b) {
            $b->nilai_aset = $b->stok * $b->harga_rata_rata;
            $nilaiAsetBahan += $b->nilai_aset;

            // Set Status
            $b->status_stok = $this->calculateStockStatus($b->stok, $b->stok_minimum);
        }

        // Summary Statistics
        $totalSkuProduk = $produks->count();
        $totalSkuBahan = $bahanBakus->count();

        // Critical Items Filter (stok < stok_minimum)
        $kritisProduks = $produks->filter(fn($p) => $p->stok < $p->stok_minimum);
        $kritisBahans = $bahanBakus->filter(fn($b) => $b->stok < $b->stok_minimum);
        $totalKritis = $kritisProduks->count() + $kritisBahans->count();

        return view('persediaan.index', compact(
            'produks', 'bahanBakus', 'adjustments', 'tab', 
            'nilaiAsetProduk', 'nilaiAsetBahan', 'totalSkuProduk', 'totalSkuBahan',
            'kritisProduks', 'kritisBahans', 'totalKritis'
        ));
    }

    /**
     * Show form to create stock adjustment.
     */
    public function createAdjustment()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat melakukan adjustment.');
        }

        $produks = Produk::where('is_active', true)->orderBy('nama_produk', 'asc')->get();
        $bahanBakus = BahanBaku::where('is_active', true)->orderBy('nama_bahan', 'asc')->get();

        return view('persediaan.adjustment', compact('produks', 'bahanBakus'));
    }

    /**
     * Store manual stock adjustment and update item stock.
     */
    public function storeAdjustment(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'item_type'   => 'required|in:produk,bahan_baku',
            'item_id'     => 'required|integer',
            'stok_fisik'  => 'required|integer|min:0',
            'alasan'      => 'required|string|max:500',
            'tanggal'     => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->item_type === 'produk') {
                $item = Produk::lockForUpdate()->findOrFail($request->item_id);
            } else {
                $item = BahanBaku::lockForUpdate()->findOrFail($request->item_id);
            }

            $stokSistem = $item->stok;
            $selisih = $request->stok_fisik - $stokSistem;

            // Save stock adjustment log
            StockAdjustment::create([
                'item_type'   => $request->item_type,
                'item_id'     => $request->item_id,
                'stok_sistem' => $stokSistem,
                'stok_fisik'  => $request->stok_fisik,
                'selisih'     => $selisih,
                'alasan'      => $request->alasan,
                'tanggal'     => $request->tanggal,
                'created_by'  => Auth::id(),
            ]);

            // Update item stock actual
            $item->update([
                'stok' => $request->stok_fisik
            ]);
        });

        return redirect()->route('persediaan.index', ['tab' => 'riwayat'])
                         ->with('success', 'Adjustment stok berhasil disimpan dan diupdate.');
    }

    /**
     * Display Kartu Stok mutasi page.
     */
    public function kartuStok(Request $request, $type, $id)
    {
        $dari = $request->get('dari') ? Carbon::parse($request->get('dari'))->startOfDay() : null;
        $sampai = $request->get('sampai') ? Carbon::parse($request->get('sampai'))->endOfDay() : null;

        if ($type === 'produk') {
            $item = Produk::findOrFail($id);
            $mutations = $this->getProductMutations($item);
        } else {
            $item = BahanBaku::findOrFail($id);
            $mutations = $this->getBahanMutations($item);
        }

        // Sort mutations chronologically (ascending) to compute correct running balances
        $mutations = $mutations->sortBy(function ($m) {
            return $m['timestamp'] . '_' . $m['id'];
        });

        // Compute running balance
        $balance = 0;
        $mutationsFormatted = collect();

        foreach ($mutations as $m) {
            $balance += ($m['masuk'] - $m['keluar']);
            $m['saldo'] = $balance;
            $mutationsFormatted->push($m);
        }

        // Apply filters
        $filteredMutations = $mutationsFormatted;
        if ($dari) {
            $filteredMutations = $filteredMutations->filter(fn($m) => Carbon::parse($m['tanggal'])->startOfDay()->greaterThanOrEqualTo($dari));
        }
        if ($sampai) {
            $filteredMutations = $filteredMutations->filter(fn($m) => Carbon::parse($m['tanggal'])->endOfDay()->lessThanOrEqualTo($sampai));
        }

        // Reverse for displaying newest first
        $filteredMutations = $filteredMutations->reverse();

        return view('persediaan.kartu_stok', compact('item', 'type', 'filteredMutations', 'dari', 'sampai'));
    }

    /**
     * Export Kartu Stok to Excel (.xlsx) using native PhpSpreadsheet.
     */
    public function export(Request $request, $type, $id)
    {
        $dari = $request->get('dari') ? Carbon::parse($request->get('dari'))->startOfDay() : null;
        $sampai = $request->get('sampai') ? Carbon::parse($request->get('sampai'))->endOfDay() : null;

        if ($type === 'produk') {
            $item = Produk::findOrFail($id);
            $mutations = $this->getProductMutations($item);
            $title = "Kartu Stok Produk Jadi - " . $item->nama_produk;
        } else {
            $item = BahanBaku::findOrFail($id);
            $mutations = $this->getBahanMutations($item);
            $title = "Kartu Stok Bahan Baku - " . $item->nama_bahan;
        }

        // Sort ascending to calculate running balance
        $mutations = $mutations->sortBy(function ($m) {
            return $m['timestamp'] . '_' . $m['id'];
        });

        $balance = 0;
        $mutationsFormatted = collect();

        foreach ($mutations as $m) {
            $balance += ($m['masuk'] - $m['keluar']);
            $m['saldo'] = $balance;
            $mutationsFormatted->push($m);
        }

        $filteredMutations = $mutationsFormatted;
        if ($dari) {
            $filteredMutations = $filteredMutations->filter(fn($m) => Carbon::parse($m['tanggal'])->startOfDay()->greaterThanOrEqualTo($dari));
        }
        if ($sampai) {
            $filteredMutations = $filteredMutations->filter(fn($m) => Carbon::parse($m['tanggal'])->endOfDay()->lessThanOrEqualTo($sampai));
        }

        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Header Informasi Laporan
        $sheet->setCellValue('A1', 'SUSU SEGAR PAK SI ERP');
        $sheet->setCellValue('A2', strtoupper($title));
        $period = "Semua Periode";
        if ($dari && $sampai) {
            $period = $dari->translatedFormat('d F Y') . " s/d " . $sampai->translatedFormat('d F Y');
        } elseif ($dari) {
            $period = "Sejak " . $dari->translatedFormat('d F Y');
        } elseif ($sampai) {
            $period = "Hingga " . $sampai->translatedFormat('d F Y');
        }
        $sheet->setCellValue('A3', 'Periode: ' . $period);
        $sheet->setCellValue('A4', 'Tanggal Export: ' . Carbon::now()->translatedFormat('d F Y H:i'));
        $sheet->setCellValue('A5', 'Diformat oleh: ' . Auth::user()->name);

        // Styling Title
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A2')->getFont()->setSize(12);

        // Metadata Item
        $sheet->setCellValue('A7', 'Kode Item:');
        $sheet->setCellValue('B7', $item->kode_produk ?? $item->kode_bahan);
        $sheet->setCellValue('A8', 'Nama Item:');
        $sheet->setCellValue('B8', $item->nama_produk ?? $item->nama_bahan);
        $sheet->setCellValue('D7', 'Satuan:');
        $sheet->setCellValue('E7', $item->satuan);
        $sheet->setCellValue('D8', 'Stok Saat Ini:');
        $sheet->setCellValue('E8', $item->stok);

        $sheet->getStyle('A7:A8')->getFont()->setBold(true);
        $sheet->getStyle('D7:D8')->getFont()->setBold(true);

        // 2. Table Column Headers
        $sheet->setCellValue('A10', 'Tanggal');
        $sheet->setCellValue('B10', 'Keterangan');
        $sheet->setCellValue('C10', 'Referensi');
        $sheet->setCellValue('D10', 'Masuk');
        $sheet->setCellValue('E10', 'Keluar');
        $sheet->setCellValue('F10', 'Saldo');

        // Style Table Headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]]
        ];
        $sheet->getStyle('A10:F10')->applyFromArray($headerStyle);
        $sheet->getRowDimension('10')->setRowHeight(25);

        // 3. Fill Data
        $rowNum = 11;
        $totalMasuk = 0;
        $totalKeluar = 0;

        // Display oldest first for standard mutasi chronological sheet
        foreach ($filteredMutations as $mut) {
            $sheet->setCellValue('A' . $rowNum, Carbon::parse($mut['tanggal'])->format('d/m/Y'));
            $sheet->setCellValue('B' . $rowNum, $mut['keterangan']);
            $sheet->setCellValue('C' . $rowNum, $mut['referensi']);
            
            $sheet->setCellValue('D' . $rowNum, $mut['masuk']);
            $sheet->setCellValue('E' . $rowNum, $mut['keluar']);
            $sheet->setCellValue('F' . $rowNum, $mut['saldo']);

            // Alignments
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $rowNum . ':F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
            // Format number as Integer
            $sheet->getStyle('D' . $rowNum . ':F' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

            $totalMasuk += $mut['masuk'];
            $totalKeluar += $mut['keluar'];

            // Light borders on data rows
            $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

            $rowNum++;
        }

        // Summary row
        $sheet->setCellValue('A' . $rowNum, 'TOTAL');
        $sheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
        $sheet->setCellValue('D' . $rowNum, $totalMasuk);
        $sheet->setCellValue('E' . $rowNum, $totalKeluar);
        
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
        
        $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
        $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output Excel response
        $writer = new Xlsx($spreadsheet);
        $filename = "kartu_stok_" . Str::slug($item->nama_produk ?? $item->nama_bahan) . "_" . Carbon::now()->format('Ymd_His') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Retrieve all mutations for a Raw Material item.
     */
    private function getBahanMutations(BahanBaku $item)
    {
        $mutations = collect();

        // 1. Purchase Inflow
        $purchases = PembelianDetail::with('pembelian')
            ->where('bahan_baku_id', $item->id)
            ->get();
        foreach ($purchases as $p) {
            $mutations->push([
                'id'         => 'pur_' . $p->id,
                'tanggal'    => $p->pembelian->tanggal_faktur,
                'timestamp'  => $p->pembelian->tanggal_faktur->format('Y-m-d') . ' ' . $p->created_at->format('H:i:s'),
                'keterangan' => 'Pembelian Bahan Baku',
                'referensi'  => $p->pembelian->nomor_faktur,
                'masuk'      => $p->kuantitas,
                'keluar'     => 0,
            ]);
        }

        // 2. Production Use Outflow
        $productionUses = ProduksiBahanPakai::with('produksi')
            ->where('bahan_baku_id', $item->id)
            ->get();
        foreach ($productionUses as $pu) {
            $date = $pu->produksi->tanggal_selesai ?? $pu->produksi->tanggal_mulai;
            $mutations->push([
                'id'         => 'pru_' . $pu->id,
                'tanggal'    => $date,
                'timestamp'  => $date->format('Y-m-d') . ' ' . $pu->created_at->format('H:i:s'),
                'keterangan' => 'Penggunaan Produksi (' . $pu->produksi->nama_batch . ')',
                'referensi'  => $pu->produksi->nomor_produksi,
                'masuk'      => 0,
                'keluar'     => (float) $pu->kuantitas_pakai,
            ]);
        }

        // 3. Stock Adjustments Inflow / Outflow
        $adjustments = StockAdjustment::where('item_type', 'bahan_baku')
            ->where('item_id', $item->id)
            ->get();
        foreach ($adjustments as $adj) {
            $mutations->push([
                'id'         => 'adj_' . $adj->id,
                'tanggal'    => $adj->tanggal,
                'timestamp'  => $adj->tanggal->format('Y-m-d') . ' ' . $adj->created_at->format('H:i:s'),
                'keterangan' => 'Adjustment: ' . $adj->alasan,
                'referensi'  => 'ADJ-' . str_pad($adj->id, 5, '0', STR_PAD_LEFT),
                'masuk'      => $adj->selisih > 0 ? $adj->selisih : 0,
                'keluar'     => $adj->selisih < 0 ? abs($adj->selisih) : 0,
            ]);
        }

        return $mutations;
    }

    /**
     * Retrieve all mutations for a Finished Product item.
     */
    private function getProductMutations(Produk $item)
    {
        $mutations = collect();

        // 1. Sales Outflow
        $sales = PenjualanDetail::with('penjualan')
            ->where('produk_id', $item->id)
            ->get();
        foreach ($sales as $s) {
            $mutations->push([
                'id'         => 'sal_' . $s->id,
                'tanggal'    => $s->penjualan->tanggal,
                'timestamp'  => $s->penjualan->tanggal->format('Y-m-d') . ' ' . $s->created_at->format('H:i:s'),
                'keterangan' => 'Penjualan Retail',
                'referensi'  => $s->penjualan->nomor_transaksi,
                'masuk'      => 0,
                'keluar'     => $s->kuantitas,
            ]);
        }

        // 2. Sales Returns Inflow
        $returns = PenjualanReturDetail::with('retur.penjualan')
            ->where('produk_id', $item->id)
            ->get();
        foreach ($returns as $r) {
            if ($r->retur->kembalikan_stok) {
                $mutations->push([
                    'id'         => 'ret_' . $r->id,
                    'tanggal'    => $r->retur->tanggal_retur,
                    'timestamp'  => $r->retur->tanggal_retur->format('Y-m-d') . ' ' . $r->created_at->format('H:i:s'),
                    'keterangan' => 'Retur Penjualan (' . $r->retur->alasan . ')',
                    'referensi'  => $r->retur->penjualan->nomor_transaksi,
                    'masuk'      => $r->kuantitas_retur,
                    'keluar'     => 0,
                ]);
            }
        }

        // 3. Production Inflow (Selesai status)
        $productions = Produksi::where('produk_output_id', $item->id)
            ->where('status', 'Selesai')
            ->get();
        foreach ($productions as $p) {
            $date = $p->tanggal_selesai ?? $p->tanggal_mulai;
            $mutations->push([
                'id'         => 'pro_' . $p->id,
                'tanggal'    => $date,
                'timestamp'  => $date->format('Y-m-d') . ' ' . $p->updated_at->format('H:i:s'),
                'keterangan' => 'Penerimaan Output Produksi',
                'referensi'  => $p->nomor_produksi,
                'masuk'      => $p->actual_output ?? $p->target_output,
                'keluar'     => 0,
            ]);
        }

        // 4. Stock Adjustments Inflow / Outflow
        $adjustments = StockAdjustment::where('item_type', 'produk')
            ->where('item_id', $item->id)
            ->get();
        foreach ($adjustments as $adj) {
            $mutations->push([
                'id'         => 'adj_' . $adj->id,
                'tanggal'    => $adj->tanggal,
                'timestamp'  => $adj->tanggal->format('Y-m-d') . ' ' . $adj->created_at->format('H:i:s'),
                'keterangan' => 'Adjustment: ' . $adj->alasan,
                'referensi'  => 'ADJ-' . str_pad($adj->id, 5, '0', STR_PAD_LEFT),
                'masuk'      => $adj->selisih > 0 ? $adj->selisih : 0,
                'keluar'     => $adj->selisih < 0 ? abs($adj->selisih) : 0,
            ]);
        }

        return $mutations;
    }

    /**
     * Compute Stock Status badge key
     */
    private function calculateStockStatus($stok, $minimum)
    {
        if ($minimum == 0) {
            return $stok > 0 ? 'Aman' : 'Kritis';
        }

        if ($stok < $minimum) {
            return 'Kritis';
        } elseif ($stok < 2 * $minimum) {
            return 'Hampir Habis';
        }
        return 'Aman';
    }
}
