<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PAYMENT_TIMEOUT_MINUTES = 10;
    
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
        'custom_photo',
    ];

    protected $casts = [
        'preparing_at' => 'datetime',
        'delivering_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Check if the order payment period has expired.
     */
    public function isExpired(): bool
    {
        if ($this->payment_status !== 'awaiting_payment') {
            return false;
        }

        return $this->created_at->addMinutes(self::PAYMENT_TIMEOUT_MINUTES)->isPast();
    }

    /**
     * Mark the order as cancelled and restore product stocks.
     */
    public function markAsCancelled(): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            // Restore stock for each item
            foreach ($this->items as $item) {
                // Use increment to stay thread-safe
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }

            // Update order status
            $this->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);
        });
    }

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
