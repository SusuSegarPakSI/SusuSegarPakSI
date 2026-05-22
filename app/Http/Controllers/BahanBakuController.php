<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of all bahan_baku.
     * Accessible by both Admin and Manajer.
     */
    public function index(): View
    {
        $bahanBakus = BahanBaku::with('supplierUtama')->latest()->paginate(15);
        return view('master.bahan-baku.index', compact('bahanBakus'));
    }

    /**
     * Show form to create a new bahan_baku. ADMIN ONLY.
     */
    public function create(): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('nama_supplier')->get();
        return view('master.bahan-baku.create', compact('suppliers'));
    }

    /**
     * Store a newly created bahan_baku. ADMIN ONLY.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bahan'        => ['required', 'string', 'max:255'],
            'kategori'          => ['required', 'in:Baku,Penolong,Kemasan'],
            'satuan'            => ['required', 'string', 'max:50'],
            'stok_minimum'      => ['required', 'integer', 'min:0'],
            'supplier_utama_id' => ['nullable', 'exists:suppliers,id'],
            'is_active'         => ['boolean'],
        ]);

        $kode = BahanBaku::generateKode();

        BahanBaku::create([
            'kode_bahan'        => $kode,
            'nama_bahan'        => $validated['nama_bahan'],
            'kategori'          => $validated['kategori'],
            'satuan'            => $validated['satuan'],
            'stok_minimum'      => $validated['stok_minimum'],
            'supplier_utama_id' => $validated['supplier_utama_id'] ?? null,
            'is_active'         => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'bahan'])
            ->with('success', "Bahan Baku {$validated['nama_bahan']} berhasil ditambahkan.");
    }

    /**
     * Show the specified bahan_baku.
     * Accessible by both Admin and Manajer.
     */
    public function show(BahanBaku $bahanBaku): View
    {
        $bahanBaku->load('supplierUtama');
        return view('master.bahan-baku.show', compact('bahanBaku'));
    }

    /**
     * Show form to edit an existing bahan_baku. ADMIN ONLY.
     */
    public function edit(BahanBaku $bahanBaku): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('nama_supplier')->get();
        return view('master.bahan-baku.edit', compact('bahanBaku', 'suppliers'));
    }

    /**
     * Update the specified bahan_baku. ADMIN ONLY.
     */
    public function update(Request $request, BahanBaku $bahanBaku): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bahan'        => ['required', 'string', 'max:255'],
            'kategori'          => ['required', 'in:Baku,Penolong,Kemasan'],
            'satuan'            => ['required', 'string', 'max:50'],
            'stok_minimum'      => ['required', 'integer', 'min:0'],
            'supplier_utama_id' => ['nullable', 'exists:suppliers,id'],
            'is_active'         => ['boolean'],
        ]);

        $bahanBaku->update([
            'nama_bahan'        => $validated['nama_bahan'],
            'kategori'          => $validated['kategori'],
            'satuan'            => $validated['satuan'],
            'stok_minimum'      => $validated['stok_minimum'],
            'supplier_utama_id' => $validated['supplier_utama_id'] ?? null,
            'is_active'         => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'bahan'])
            ->with('success', "Bahan Baku {$bahanBaku->nama_bahan} berhasil diperbarui.");
    }

    /**
     * Toggle is_active status. ADMIN ONLY.
     */
    public function toggle(BahanBaku $bahanBaku): RedirectResponse
    {
        $bahanBaku->is_active = !$bahanBaku->is_active;
        $bahanBaku->save();

        $status = $bahanBaku->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('master.index', ['tab' => 'bahan'])
            ->with('success', "Bahan Baku {$bahanBaku->nama_bahan} berhasil {$status}.");
    }
}
