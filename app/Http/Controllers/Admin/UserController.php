<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $users = User::query()
            ->with('roles')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->assignRole($validated['role']);

        $activityLog->log(
            event: 'user_created',
            description: 'User baru dibuat: ' . $user->email,
            subject: $user,
            properties: [
                'role' => $validated['role'],
            ],
            user: $request->user(),
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('pages.admin.users.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::orderBy('name')->get(),
            'currentRole' => $user->roles->first()?->name,
        ]);
    }

    public function update(Request $request, User $user, ActivityLogService $activityLog): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first()?->name,
        ];

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);
        $user->syncRoles([$validated['role']]);

        $activityLog->log(
            event: 'user_updated',
            description: 'User diperbarui: ' . $user->email,
            subject: $user,
            properties: [
                'old' => $oldData,
                'new' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $validated['role'],
                    'password_changed' => ! empty($validated['password']),
                ],
            ],
            user: $request->user(),
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user, ActivityLogService $activityLog): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        if (
            method_exists($user, 'sales') && $user->sales()->exists()
            || method_exists($user, 'purchases') && $user->purchases()->exists()
            || method_exists($user, 'stockMovements') && $user->stockMovements()->exists()
        ) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'User tidak bisa dihapus karena sudah memiliki transaksi atau aktivitas stok.');
        }

        $deletedUserData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->values()->all(),
        ];

        $user->delete();

        $activityLog->log(
            event: 'user_deleted',
            description: 'User dihapus: ' . $deletedUserData['email'],
            subject: null,
            properties: [
                'deleted_user' => $deletedUserData,
            ],
            user: $request->user(),
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}