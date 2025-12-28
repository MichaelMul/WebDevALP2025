<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'address',
        'postal_code',
        'city',
        'wallet_balance',
        'points',
        'membership_tier',
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:2',
        'points' => 'integer',
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

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class);
    }
}
