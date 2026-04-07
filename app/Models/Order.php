<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'preparing_at',
        'delivering_at',
        'completed_at',
        'address',
        'phone_number',
        'notes',
        'payment_receipt',
        'payment_status',
        'courier_type',
        'shipping_cost',
        'petugas_id',
    ];

    protected $casts = [
        'preparing_at' => 'datetime',
        'delivering_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
