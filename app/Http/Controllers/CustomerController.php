<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private function canAccessCustomer(Customer $customer): void
    {
        $user = auth()->user();

        if ($user->role && $user->role->slug === 'super-admin') {
            return;
        }

        if ($user->role && $user->role->slug === 'manager') {
            if ($customer->department_id === $user->department_id) {
                return;
            }
        }

        if ($customer->assigned_to === $user->id) {
            return;
        }

        abort(403, 'Bu müşteriye erişim yetkiniz yok.');
    }

    public function index(): View
    {
        $user = auth()->user();

        if ($user->role && $user->role->slug === 'super-admin') {
            $customers = Customer::with(['department', 'assignedUser', 'createdByUser'])->get();
        } elseif ($user->role && $user->role->slug === 'manager') {
            $customers = Customer::with(['department', 'assignedUser', 'createdByUser'])
                ->where('department_id', $user->department_id)
                ->get();
        } else {
            $customers = Customer::with(['department', 'assignedUser', 'createdByUser'])
                ->where('assigned_to', $user->id)
                ->get();
        }

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:customers,email'],
            'password' => ['required', 'min:6'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $user = auth()->user();
        $departmentId = $request->department_id ?? $user->department_id;

        // Auto-assign logic (Least workload)
        $assignedTo = User::where('department_id', $departmentId)
            ->where('is_active', true)
            ->withCount('assignedCustomers') // Bu ilişkiyi Model'e eklemeliyiz
            ->orderBy('assigned_customers_count', 'asc')
            ->first()?->id ?? $user->id;

        Customer::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $departmentId,
            'assigned_to' => $assignedTo,
            'created_by' => $user->id,
            'status' => 'pending_profile',
            'is_active' => true,
        ]);

        return redirect()->route('customers.index')->with('success', 'Müşteri başarıyla oluşturuldu ve en müsait temsilciye atandı.');
    }

    public function show(Customer $customer): View
    {
        $this->canAccessCustomer($customer);

        $customer->load(['department', 'assignedUser', 'createdByUser']);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $this->canAccessCustomer($customer);
        $user = auth()->user();

        // Admin ise tüm çalışanlar, Manager ise sadece kendi departmanı
        if ($user->role && $user->role->slug === 'super-admin') {
            $users = User::where('is_active', true)->get();
        } else {
            $users = User::where('department_id', $user->department_id)
                ->where('is_active', true)
                ->get();
        }

        return view('customers.edit', compact('customer', 'users'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $this->canAccessCustomer($customer);

        $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:255'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $customer->update([
            'full_name' => $request->full_name,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('customers.index')->with('success', 'Müşteri başarıyla güncellendi.');
    }
}