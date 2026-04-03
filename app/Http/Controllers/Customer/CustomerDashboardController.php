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
        
        // Aktiviteleri topla
        $activities = collect();

        // 1. Hesap Oluşturma Aktivitesi
        $activities->push((object)[
            'type' => 'account_created',
            'title' => 'Hesap Oluşturuldu',
            'description' => 'CRM sistemine üyeliğiniz gerçekleştirildi.',
            'timestamp' => $customer->created_at,
            'icon' => '🎉'
        ]);

        // 2. Profil Güncelleme Aktivitesi
        if ($customer->updated_at > $customer->created_at) {
            $activities->push((object)[
                'type' => 'profile_updated',
                'title' => 'Profil Güncellendi',
                'description' => 'Profil bilgileriniz düzenlendi.',
                'timestamp' => $customer->updated_at,
                'icon' => '👤'
            ]);
        }

        // 3. Son Mesaj Aktiviteleri (Son 5 mesaj)
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

        if ($conversation) {
            $recentMessages = $conversation->messages()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($recentMessages as $message) {
                $isFromCustomer = ($message->sender_type === 'customer');
                $activities->push((object)[
                    'type' => 'message',
                    'title' => $isFromCustomer ? 'Mesaj Gönderildi' : 'Yeni Mesaj Alındı',
                    'description' => \Illuminate\Support\Str::limit($message->message, 50),
                    'timestamp' => $message->created_at,
                    'icon' => $isFromCustomer ? '📤' : '📧'
                ]);
            }
        }

        // Aktiviteleri tarihe göre sırala
        $activities = $activities->sortByDesc('timestamp')->values();

        return view('customer.dashboard', compact('customer', 'activities', 'conversation'));
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
