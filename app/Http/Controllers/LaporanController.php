<?php

namespace App\Http\Controllers;

use App\Models\BebanLain;
use App\Models\Pengaturan;
use App\Services\LaporanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanController extends Controller
{
    private LaporanService $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    /**
     * Main Laporan page with 4 KPI cards, 3 tabs (Laba Rugi, Arus Kas, Neraca),
     * and optional Beban Lain management + Saldo Awal setting.
     */
    public function index(Request $request)
    {
        $dari = Carbon::parse($request->input('dari', now()->startOfMonth()->toDateString()));
        $sampai = Carbon::parse($request->input('sampai', now()->toDateString()));

        $labaRugi = $this->laporanService->hitungLabaRugi($dari, $sampai);
        $arusKas  = $this->laporanService->hitungArusKas($dari, $sampai);
        $neraca   = $this->laporanService->hitungNeraca($sampai);

        // Beban Lain list (for admin tab)
        $bebanLainList = BebanLain::whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
            ->orderBy('tanggal', 'desc')
            ->get();

        $saldoKasAwal = (float) Pengaturan::getVal('saldo_kas_awal', 0);

        return view('laporan.index', compact(
            'dari', 'sampai', 'labaRugi', 'arusKas', 'neraca',
            'bebanLainList', 'saldoKasAwal'
        ));
    }

    /**
     * Admin Only: Store Beban Lain entry.
     */
    public function storeBebanLain(Request $request)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'keterangan'  => 'required|string|max:255',
            'nominal'     => 'required|numeric|min:1',
        ]);

        BebanLain::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('laporan.index', [
            'dari'   => $request->input('dari'),
            'sampai' => $request->input('sampai'),
            'tab'    => 'beban',
        ])->with('success', 'Beban berhasil ditambahkan.');
    }

    /**
     * Admin Only: Delete Beban Lain entry.
     */
    public function destroyBebanLain(BebanLain $bebanLain)
    {
        $this->authorize('admin');

        $bebanLain->delete();

        return redirect()->back()->with('success', 'Beban berhasil dihapus.');
    }

    /**
     * Admin Only: Set Saldo Kas Awal.
     */
    public function setSaldoAwal(Request $request)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'saldo_kas_awal' => 'required|numeric|min:0',
        ]);

        Pengaturan::setVal('saldo_kas_awal', $validated['saldo_kas_awal']);

        return redirect()->route('laporan.index', [
            'dari'   => $request->input('dari'),
            'sampai' => $request->input('sampai'),
            'tab'    => 'arus_kas',
        ])->with('success', 'Saldo kas awal berhasil diperbarui.');
    }

    /**
     * Export Laba Rugi as .xlsx
     */
    public function exportLabaRugi(Request $request)
    {
        $dari = Carbon::parse($request->input('dari', now()->startOfMonth()->toDateString()));
        $sampai = Carbon::parse($request->input('sampai', now()->toDateString()));

        $data = $this->laporanService->hitungLabaRugi($dari, $sampai);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laba Rugi');

        // Header
        $sheet->setCellValue('A1', 'LAPORAN LABA RUGI');
        $sheet->setCellValue('A2', 'Susu Segar Pak Si');
        $sheet->setCellValue('A3', 'Periode: ' . $dari->translatedFormat('d M Y') . ' - ' . $sampai->translatedFormat('d M Y'));
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');
        $sheet->getStyle('A1:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);

        $row = 5;
        $items = [
            ['Omzet Penjualan', $data['omzet']],
            ['(-) Retur Penjualan', -$data['total_retur']],
            ['Penjualan Bersih', $data['penjualan_bersih'], true],
            ['(-) HPP Terjual', -$data['hpp_terjual']],
            ['Laba Kotor', $data['laba_kotor'], true],
        ];

        foreach ($items as $item) {
            $sheet->setCellValue("A{$row}", $item[0]);
            $sheet->setCellValue("C{$row}", $item[1]);
            $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0');
            if (!empty($item[2])) {
                $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$row}:C{$row}")->getBorders()->getTop()
                    ->setBorderStyle(Border::BORDER_THIN);
            }
            $row++;
        }

        $row++;
        $sheet->setCellValue("A{$row}", 'Beban Operasional:');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        foreach ($data['breakdown_beban'] as $beban) {
            $sheet->setCellValue("B{$row}", $beban['nama']);
            $sheet->setCellValue("C{$row}", -$beban['nominal']);
            $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }

        $sheet->setCellValue("A{$row}", 'Total Beban Operasional');
        $sheet->setCellValue("C{$row}", -$data['total_beban_operasional']);
        $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
        $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("A{$row}:C{$row}")->getBorders()->getTop()
            ->setBorderStyle(Border::BORDER_THIN);
        $row += 2;

        $sheet->setCellValue("A{$row}", 'LABA BERSIH');
        $sheet->setCellValue("C{$row}", $data['laba_bersih']);
        $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("A{$row}:C{$row}")->getBorders()->getTop()
            ->setBorderStyle(Border::BORDER_DOUBLE);
        $sheet->getStyle("A{$row}:C{$row}")->getBorders()->getBottom()
            ->setBorderStyle(Border::BORDER_DOUBLE);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);

        $filename = 'Laba_Rugi_' . $dari->format('Ymd') . '_' . $sampai->format('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Export Arus Kas as .xlsx
     */
    public function exportArusKas(Request $request)
    {
        $dari = Carbon::parse($request->input('dari', now()->startOfMonth()->toDateString()));
        $sampai = Carbon::parse($request->input('sampai', now()->toDateString()));

        $data = $this->laporanService->hitungArusKas($dari, $sampai);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Arus Kas');

        $sheet->setCellValue('A1', 'LAPORAN ARUS KAS');
        $sheet->setCellValue('A2', 'Susu Segar Pak Si');
        $sheet->setCellValue('A3', 'Periode: ' . $dari->translatedFormat('d M Y') . ' - ' . $sampai->translatedFormat('d M Y'));
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');
        $sheet->getStyle('A1:B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);

        $row = 5;
        $items = [
            ['Saldo Kas Awal', $data['saldo_awal'], true],
            ['', null],
            ['KAS MASUK', null, true],
            ['Penerimaan Penjualan', $data['kas_masuk_penjualan']],
            ['Total Kas Masuk', $data['total_kas_masuk'], true],
            ['', null],
            ['KAS KELUAR', null, true],
            ['Pembelian Bahan (Tunai)', $data['kas_keluar_pembelian_lunas']],
            ['Pembayaran Hutang Supplier', $data['kas_keluar_pembayaran_hutang']],
            ['Beban Lain-lain', $data['kas_keluar_beban_lain']],
            ['Biaya Produksi', $data['kas_keluar_biaya_produksi']],
            ['Total Kas Keluar', $data['total_kas_keluar'], true],
            ['', null],
            ['Net Cash Flow', $data['net_cash_flow'], true],
            ['SALDO KAS AKHIR', $data['saldo_akhir'], true],
        ];

        foreach ($items as $item) {
            $sheet->setCellValue("A{$row}", $item[0]);
            if ($item[1] !== null) {
                $sheet->setCellValue("B{$row}", $item[1]);
                $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('#,##0');
            }
            if (!empty($item[2])) {
                $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
            }
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);

        $filename = 'Arus_Kas_' . $dari->format('Ymd') . '_' . $sampai->format('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Export Neraca as .xlsx
     */
    public function exportNeraca(Request $request)
    {
        $sampai = Carbon::parse($request->input('sampai', now()->toDateString()));

        $data = $this->laporanService->hitungNeraca($sampai);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Neraca');

        $sheet->setCellValue('A1', 'NERACA');
        $sheet->setCellValue('A2', 'Susu Segar Pak Si');
        $sheet->setCellValue('A3', 'Per ' . $sampai->translatedFormat('d F Y'));
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');
        $sheet->getStyle('A1:B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);

        $row = 5;
        $items = [
            ['ASET', null, true],
            ['Kas', $data['aset']['kas']],
            ['Piutang Usaha', $data['aset']['piutang']],
            ['Persediaan Produk', $data['aset']['persediaan_produk']],
            ['Persediaan Bahan Baku', $data['aset']['persediaan_bahan']],
            ['TOTAL ASET', $data['aset']['total_aset'], true],
            ['', null],
            ['KEWAJIBAN', null, true],
            ['Hutang Usaha', $data['kewajiban']['hutang_usaha']],
            ['TOTAL KEWAJIBAN', $data['kewajiban']['total_kewajiban'], true],
            ['', null],
            ['EKUITAS', null, true],
            ['Modal/Ekuitas', $data['ekuitas']['modal_ekuitas']],
            ['', null],
            ['TOTAL KEWAJIBAN + EKUITAS', $data['total_kewajiban_ekuitas'], true],
        ];

        foreach ($items as $item) {
            $sheet->setCellValue("A{$row}", $item[0]);
            if ($item[1] !== null) {
                $sheet->setCellValue("B{$row}", $item[1]);
                $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('#,##0');
            }
            if (!empty($item[2])) {
                $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
            }
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);

        $filename = 'Neraca_' . $sampai->format('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Authorize admin role.
     */
    private function authorize(string $role): void
    {
        if (auth()->user()->role !== $role) {
            abort(403, 'Akses ditolak.');
        }
    }
}
