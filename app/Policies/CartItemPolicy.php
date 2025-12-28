<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CartItem;

class CartItemPolicy
{
    public function own(User $user, CartItem $cartItem): bool
    {
        return $user->id === $cartItem->user_id;
    }
}
