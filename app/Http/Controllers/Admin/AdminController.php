<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $users = User::with(['department', 'role'])->get();

        return view('admin.index', compact('users'));
    }

    public function edit(User $user): View
    {
        $departments = Department::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->get();

        return view('admin.edit-user', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update([
            'department_id' => $request->department_id,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }
    public function monitoring(): View
    {
        $conversations = \App\Models\Conversation::with(['customer', 'user', 'messages'])->latest()->get();

        return view('admin.monitoring', compact('conversations'));
    }
}