<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of all customers.
     * Accessible by both Admin and Manajer.
     */
    public function index(): View
    {
        $customers = Customer::latest()->paginate(15);
        return view('master.pelanggan.index', compact('customers'));
    }

    /**
     * Show form to create a new customer. ADMIN ONLY.
     */
    public function create(): View
    {
        return view('master.pelanggan.create');
    }

    /**
     * Store a newly created customer. ADMIN ONLY.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'tipe'      => ['required', 'in:Perorangan,Mitra'],
            'telepon'   => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:255'],
            'alamat'    => ['nullable', 'string'],
            'catatan'   => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $kode = Customer::generateKode();

        Customer::create([
            'kode_pelanggan' => $kode,
            'nama'           => $validated['nama'],
            'tipe'           => $validated['tipe'],
            'telepon'        => $validated['telepon'] ?? null,
            'email'          => $validated['email'] ?? null,
            'alamat'         => $validated['alamat'] ?? null,
            'catatan'        => $validated['catatan'] ?? null,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'pelanggan'])
            ->with('success', "Pelanggan {$validated['nama']} berhasil ditambahkan.");
    }

    /**
     * Show the specified customer.
     * Accessible by both Admin and Manajer.
     */
    public function show(Customer $pelanggan): View
    {
        return view('master.pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show form to edit an existing customer. ADMIN ONLY.
     */
    public function edit(Customer $pelanggan): View
    {
        return view('master.pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified customer. ADMIN ONLY.
     */
    public function update(Request $request, Customer $pelanggan): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'tipe'      => ['required', 'in:Perorangan,Mitra'],
            'telepon'   => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:255'],
            'alamat'    => ['nullable', 'string'],
            'catatan'   => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $pelanggan->update([
            'nama'    => $validated['nama'],
            'tipe'    => $validated['tipe'],
            'telepon' => $validated['telepon'] ?? null,
            'email'   => $validated['email'] ?? null,
            'alamat'  => $validated['alamat'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master.index', ['tab' => 'pelanggan'])
            ->with('success', "Pelanggan {$pelanggan->nama} berhasil diperbarui.");
    }

    /**
     * Toggle is_active status. ADMIN ONLY.
     */
    public function toggle(Customer $pelanggan): RedirectResponse
    {
        $pelanggan->is_active = !$pelanggan->is_active;
        $pelanggan->save();

        $status = $pelanggan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('master.index', ['tab' => 'pelanggan'])
            ->with('success', "Pelanggan {$pelanggan->nama} berhasil {$status}.");
    }
}
