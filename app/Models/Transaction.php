<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'method',
        'payment_method',
        'proof_image',
        'status',
        'shipping_method',
        'shipping_cost',
        'selected_address_name',
        'selected_address_phone',
        'selected_address_jalan',
        'recipient_name',
        'phone_number',
        'full_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}