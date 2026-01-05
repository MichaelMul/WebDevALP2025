<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'courier_id',
        'order_number',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class)->nullable();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function orderTracking()
    {
        return $this->hasMany(OrderTracking::class);
    }

    public function cancellation()
    {
        return $this->hasOne(Cancellation::class);
    }
}
