<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();
        return view('customer.dashboard', compact('customer'));
    }

    public function editProfile(): View
    {
        $customer = auth('customer')->user();
        return view('customer.edit-profile', compact('customer'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $customer = auth('customer')->user();

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $customer->update([
            'full_name' => $request->full_name,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Profiliniz başarıyla güncellendi.');
    }

    public function messages(): View
    {
        $customer = auth('customer')->user();

        $conversation = Conversation::firstOrCreate(
            ['customer_id' => $customer->id],
            [
                'user_id' => $customer->assigned_to,
                'subject' => 'Müşteri Destek Sohbeti',
            ]
        );

        // Eğer atanmış kullanıcı sonradan değiştiyse veya atandıysa güncelle
        if (!$conversation->user_id && $customer->assigned_to) {
            $conversation->user_id = $customer->assigned_to;
            $conversation->save();
        }

        // Karşı tarafın (çalışanın) mesajlarını okundu işaretle
        $conversation->messages()
            ->where('sender_type', 'employee')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['messages', 'user']);

        return view('customer.messages', compact('conversation'));
    }

    public function storeMessage(Request $request): RedirectResponse
    {
        $customer = auth('customer')->user();

        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $conversation = Conversation::firstOrCreate(
            ['customer_id' => $customer->id],
            [
                'user_id' => $customer->assigned_to,
                'subject' => 'Müşteri Destek Sohbeti',
            ]
        );

        $message = $conversation->messages()->create([
            'sender_type' => 'customer',
            'sender_id' => $customer->id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return back();
    }
}
