<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public Message $message)
    {
    }

    public function broadcastOn(): array
    {
        $channels = [
            new \Illuminate\Broadcasting\PresenceChannel('conversation.' . $this->message->conversation_id),
            new PrivateChannel('admin.monitoring'),
        ];

        // Müşteri mesaj attıysa
        if ($this->message->sender_type === 'customer') {
            // 1. Atanan Temsilciye Bildirim
            $channels[] = new PrivateChannel('user.' . $this->message->conversation->user_id);
            
            // 2. Departman Yöneticisine Bildirim (Eğer departman varsa)
            if ($this->message->conversation->customer && $this->message->conversation->customer->department_id) {
                $channels[] = new PrivateChannel('department.' . $this->message->conversation->customer->department_id);
            }
        } else {
            // Çalışan gönderdiyse müşteriye bildirim gitmeli
            $channels[] = new PrivateChannel('customer.' . $this->message->conversation->customer_id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $senderName = 'Sistem';
        if ($this->message->sender_type === 'customer') {
            $senderName = $this->message->conversation->customer->full_name ?? $this->message->conversation->customer->email;
        } else {
            $senderName = $this->message->conversation->user->name ?? 'Temsilci';
        }

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_type' => $this->message->sender_type,
            'sender_name' => $senderName,
            'sender_id' => $this->message->sender_id,
            'message' => $this->message->message,
            'created_at' => optional($this->message->created_at)->format('d.m.Y H:i:s'),
        ];
    }
}