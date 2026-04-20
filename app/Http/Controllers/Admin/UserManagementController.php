<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'skills', 'needs', 'transactions', 'receivedRatings']);

        return view('admin.users.show', compact('user'));
    }

    public function ban(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot ban your own account.');
        }

        $user->update([
            'account_status' => 'banned',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User banned successfully.');
    }

    public function reactivate(User $user): RedirectResponse
    {
        $user->update([
            'account_status' => 'active',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User reactivated successfully.');
    }
}
