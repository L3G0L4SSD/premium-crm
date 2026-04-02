<?php
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{conversationId}', function ($authUser, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    $canAccess = false;
    $type = 'unknown';
    $name = 'Unknown';

    if ($authUser instanceof User) {
        if (($authUser->role && $authUser->role->slug === 'super-admin') || $conversation->user_id === $authUser->id) {
            $canAccess = true;
            $type = 'employee';
            $name = $authUser->name;
        }
    }

    if ($authUser instanceof Customer) {
        if ($conversation->customer_id === $authUser->id) {
            $canAccess = true;
            $type = 'customer';
            $name = $authUser->full_name ?? $authUser->email;
        }
    }

    if ($canAccess) {
        return [
            'id' => $authUser->id,
            'name' => $name,
            'type' => $type,
        ];
    }

    return false;
}, ['guards' => ['web', 'customer']]);

Broadcast::channel('admin.monitoring', function ($user) {
    return $user instanceof User && $user->role && $user->role->slug === 'super-admin';
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user instanceof User;
});

Broadcast::channel('customer.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user instanceof Customer;
}, ['guards' => ['customer']]);

Broadcast::channel('department.{id}', function ($user, $id) {
    if (!$user instanceof User || !$user->role) return false;
    
    // Eğer Manager ise ve kendi departmanıysa izin ver
    if ($user->role->slug === 'manager' && (int)$user->department_id === (int)$id) {
        return true;
    }
    
    // Admin her departmanı dinleyebilir
    return $user->role->slug === 'super-admin';
});