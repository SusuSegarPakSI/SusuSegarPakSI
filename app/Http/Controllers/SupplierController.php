<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display a listing of all suppliers.
     * Accessible by both Admin and Manajer.
     */
    public function index(): View
    {
        $suppliers = Supplier::latest()->paginate(15);
        return view('master.supplier.index', compact('suppliers'));
    }

    /**
     * Show form to create a new supplier. ADMIN ONLY.
     */
    public function create(): View
    {
        return view('master.supplier.create');
    }

    /**
     * Store a newly created supplier. ADMIN ONLY.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:255'],
            'nama_kontak'   => ['nullable', 'string', 'max:255'],
            'telepon'       => ['nullable', 'string', 'max:20'],
            'alamat'        => ['nullable', 'string'],
            'is_active'     => ['boolean'],
        ]);

        $kode = Supplier::generateKode();

        Supplier::create([
            'kode_supplier' => $kode,
            'nama_supplier' => $validated['nama_supplier'],
            'nama_kontak'   => $validated['nama_kontak'] ?? null,
            'telepon'       => $validated['telepon'] ?? null,
            'alamat'        => $validated['alamat'] ?? null,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'supplier'])
            ->with('success', "Supplier {$validated['nama_supplier']} berhasil ditambahkan.");
    }

    /**
     * Show the specified supplier.
     * Accessible by both Admin and Manajer.
     */
    public function show(Supplier $supplier): View
    {
        $supplier->load('bahanBakus');
        return view('master.supplier.show', compact('supplier'));
    }

    /**
     * Show form to edit an existing supplier. ADMIN ONLY.
     */
    public function edit(Supplier $supplier): View
    {
        return view('master.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier. ADMIN ONLY.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:255'],
            'nama_kontak'   => ['nullable', 'string', 'max:255'],
            'telepon'       => ['nullable', 'string', 'max:20'],
            'alamat'        => ['nullable', 'string'],
            'is_active'     => ['boolean'],
        ]);

        $supplier->update([
            'nama_supplier' => $validated['nama_supplier'],
            'nama_kontak'   => $validated['nama_kontak'] ?? null,
            'telepon'       => $validated['telepon'] ?? null,
            'alamat'        => $validated['alamat'] ?? null,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'supplier'])
            ->with('success', "Supplier {$supplier->nama_supplier} berhasil diperbarui.");
    }

    /**
     * Toggle is_active status. ADMIN ONLY.
     */
    public function toggle(Supplier $supplier): RedirectResponse
    {
        $supplier->is_active = !$supplier->is_active;
        $supplier->save();

        $status = $supplier->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('master.index', ['tab' => 'supplier'])
            ->with('success', "Supplier {$supplier->nama_supplier} berhasil {$status}.");
    }
}
