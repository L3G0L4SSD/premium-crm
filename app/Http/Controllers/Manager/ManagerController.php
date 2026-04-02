<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManagerController extends Controller
{
    public function index(): View
    {
        $departmentId = auth()->user()->department_id;
        $users = User::with(['department', 'role'])->where('department_id', $departmentId)->get();

        return view('manager.index', compact('users'));
    }
    public function edit(User $user): View
    {
        $departmentId = auth()->user()->department_id;
        $user = User::with(['department', 'role'])->where('department_id', $departmentId)->findOrFail($user->id);

        $departments = Department::where('id', $departmentId)->get();
        $roles = Role::where('is_active', true)->get();

        return view('manager.edit', compact('user', 'departments', 'roles'));
    }
    public function update(Request $request, User $user): RedirectResponse
    {
        $departmentId = auth()->user()->department_id;
        $user = User::where('department_id', $departmentId)->findOrFail($user->id);

        $request->validate([
            'role_id' => ['nullable', 'exists:roles,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update([
            'role_id' => $request->role_id,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('manager.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }
}
