<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users (excluding the currently authenticated manager).
     */
    public function index(): View
    {
        $users = User::where('id', '!=', auth()->id())
            ->latest()
            ->paginate(15);

        return view('pengaturan.users.index', compact('users'));
    }

    /**
     * Store a newly created Admin user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Akun Admin berhasil ditambahkan.');
    }

    /**
     * Update the specified Admin user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Safety check: Cannot update own account from this view
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat mengubah akun Anda sendiri dari sini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'Akun Admin berhasil diperbarui.');
    }

    /**
     * Toggle the active status of the specified Admin user.
     */
    public function toggle(User $user): RedirectResponse
    {
        // Safety check: Cannot toggle own account status
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('users.index')
            ->with('success', "Akun Admin {$user->name} berhasil {$statusStr}.");
    }
}
