<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CartController extends Controller
{
    use AuthorizesRequests;
    
    // Display cart
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Add item to cart
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->back()->with('success', $product->name . ' added to cart!');
    }

    // Remove item from cart
    public function remove(CartItem $cartItem)
    {
        $this->authorize('own', $cartItem);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    // Update quantity
    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('own', $cartItem);

        $quantity = $request->input('quantity', 1);

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }

        return redirect()->back()->with('success', 'Cart updated');
    }

    // Clear cart
    public function clear()
    {
        auth()->user()->cartItems()->delete();

        return redirect()->back()->with('success', 'Cart cleared');
    }

    // ===== ADMIN CRUD =====

    /**
     * Display all cart items (admin)
     */
    public function adminIndex()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $cartItems = CartItem::with(['user', 'product'])
            ->latest()
            ->paginate(20);

        return view('admin.cart_items.index', compact('cartItems'));
    }

    /**
     * Display cart items for specific user (admin)
     */
    public function show(CartItem $cartItem)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $cartItem->load(['user', 'product']);
        return view('admin.cart_items.show', compact('cartItem'));
    }

    /**
     * Delete cart item (admin)
     */
    public function adminDestroy(CartItem $cartItem)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('admin.cart-items.index')->with('success', 'Cart item deleted successfully');
    }

    /**
     * Clear all abandoned carts (admin)
     */
    public function clearAbandoned()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Delete cart items older than 7 days
        $deleted = CartItem::where('created_at', '<', now()->subDays(7))->delete();

        return redirect()->route('admin.cart-items.index')->with('success', "Cleared {$deleted} abandoned cart items");
    }
}
