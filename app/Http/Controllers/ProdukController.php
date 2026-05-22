<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProdukController extends Controller
{
    /**
     * Display a listing of all produk.
     * Accessible by both Admin and Manajer.
     */
    public function index(): View
    {
        $produks = Produk::latest()->paginate(15);
        return view('master.produk.index', compact('produks'));
    }

    /**
     * Show form to create a new produk. ADMIN ONLY.
     */
    public function create(): View
    {
        return view('master.produk.create');
    }

    /**
     * Store a newly created produk. ADMIN ONLY.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_produk'   => ['required', 'string', 'max:255'],
            'kategori'      => ['nullable', 'string', 'max:100'],
            'satuan'        => ['required', 'string', 'max:50'],
            'deskripsi'     => ['nullable', 'string'],
            'harga_jual'    => ['required', 'numeric', 'min:0'],
            'stok_minimum'  => ['required', 'integer', 'min:0'],
            'foto'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'     => ['boolean'],
        ]);

        $kode = Produk::generateKode();

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk', 'public');
        }

        Produk::create([
            'kode_produk'  => $kode,
            'nama_produk'  => $validated['nama_produk'],
            'kategori'     => $validated['kategori'] ?? null,
            'satuan'       => $validated['satuan'],
            'deskripsi'    => $validated['deskripsi'] ?? null,
            'harga_jual'   => $validated['harga_jual'],
            'stok_minimum' => $validated['stok_minimum'],
            'foto'         => $fotoPath,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'produk'])
            ->with('success', "Produk {$validated['nama_produk']} berhasil ditambahkan.");
    }

    /**
     * Show the specified produk.
     * Accessible by both Admin and Manajer.
     */
    public function show(Produk $produk): View
    {
        return view('master.produk.show', compact('produk'));
    }

    /**
     * Show form to edit an existing produk. ADMIN ONLY.
     */
    public function edit(Produk $produk): View
    {
        return view('master.produk.edit', compact('produk'));
    }

    /**
     * Update the specified produk. ADMIN ONLY.
     */
    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate([
            'nama_produk'   => ['required', 'string', 'max:255'],
            'kategori'      => ['nullable', 'string', 'max:100'],
            'satuan'        => ['required', 'string', 'max:50'],
            'deskripsi'     => ['nullable', 'string'],
            'harga_jual'    => ['required', 'numeric', 'min:0'],
            'stok_minimum'  => ['required', 'integer', 'min:0'],
            'foto'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'     => ['boolean'],
        ]);

        $fotoPath = $produk->foto;
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $fotoPath = $request->file('foto')->store('produk', 'public');
        }

        $produk->update([
            'nama_produk'  => $validated['nama_produk'],
            'kategori'     => $validated['kategori'] ?? null,
            'satuan'       => $validated['satuan'],
            'deskripsi'    => $validated['deskripsi'] ?? null,
            'harga_jual'   => $validated['harga_jual'],
            'stok_minimum' => $validated['stok_minimum'],
            'foto'         => $fotoPath,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'produk'])
            ->with('success', "Produk {$produk->nama_produk} berhasil diperbarui.");
    }

    /**
     * Toggle is_active status. ADMIN ONLY.
     */
    public function toggle(Produk $produk): RedirectResponse
    {
        $produk->is_active = !$produk->is_active;
        $produk->save();

        $status = $produk->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('master.index', ['tab' => 'produk'])
            ->with('success', "Produk {$produk->nama_produk} berhasil {$status}.");
    }
}
