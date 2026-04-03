<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Events\MessageSent;
class EmployeeMessageController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user?->role?->slug === 'super-admin') {
            $conversations = Conversation::with(['customer', 'user'])
                ->withCount(['messages as unread_count' => function ($query) {
                    $query->where('sender_type', 'customer')->whereNull('read_at');
                }])
                ->latest()
                ->get();
        } else {
            $conversations = Conversation::with(['customer', 'user'])
                ->where('user_id', $user->id)
                ->withCount(['messages as unread_count' => function ($query) {
                    $query->where('sender_type', 'customer')->whereNull('read_at');
                }])
                ->latest()
                ->get();
        }

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $user = auth()->user();

        if ($user?->role?->slug !== 'super-admin') {
            if ($conversation->user_id !== $user->id) {
                abort(403, 'Bu konuşmaya erişim yetkiniz yok.');
            }
        }

        // Karşı tarafın (müşterinin) mesajlarını okundu işaretle
        $conversation->messages()
            ->where('sender_type', 'customer')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['customer', 'user', 'messages']);

        return view('messages.show', compact('conversation'));
    }

    public function store(Request $request, Conversation $conversation): RedirectResponse
    {
        $user = auth()->user();

        if ($user?->role?->slug !== 'super-admin') {
            if ($conversation->user_id !== $user->id) {
                abort(403, 'Bu konuşmaya erişim yetkiniz yok.');
            }
        }

        $request->validate([
            'message' => ['required'],
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'employee',
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        event(new MessageSent($message));

        return back();
    }

    public function start(\App\Models\Customer $customer): RedirectResponse
    {
        $conversation = Conversation::firstOrCreate(
            ['customer_id' => $customer->id],
            [
                'user_id' => $customer->assigned_to ?? auth()->id(),
                'subject' => $customer->full_name . ' ile Sohbet',
            ]
        );

        return redirect()->route('messages.show', $conversation);
    }
}