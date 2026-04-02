<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
#[Fillable([
    'email',
    'password',
    'full_name',
    'company_name',
    'phone',
    'address',
    'status',
    'department_id',
    'assigned_to',
    'created_by',
    'is_active'
])]
#[Hidden(['password', 'remember_token'])]
class Customer extends Authenticatable
{
    use HasFactory, Notifiable;
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }
}