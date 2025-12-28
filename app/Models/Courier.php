<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'vehicle_type',
        'is_available',
        'current_latitude',
        'current_longitude',
        'ratings',
        'total_deliveries',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'ratings' => 'decimal:2',
        'total_deliveries' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
