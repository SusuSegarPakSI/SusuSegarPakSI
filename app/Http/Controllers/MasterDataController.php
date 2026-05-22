<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    /**
     * Unified index view showing all master data in tabs.
     * Accessible by both Admin and Manajer.
     */
    public function index(): View
    {
        $produks    = Produk::latest()->paginate(15, ['*'], 'produk_page');
        $bahanBakus = BahanBaku::with('supplierUtama')->latest()->paginate(15, ['*'], 'bahan_page');
        $customers  = Customer::latest()->paginate(15, ['*'], 'pelanggan_page');
        $suppliers  = Supplier::latest()->paginate(15, ['*'], 'supplier_page');

        return view('master.index', compact('produks', 'bahanBakus', 'customers', 'suppliers'));
    }
}
