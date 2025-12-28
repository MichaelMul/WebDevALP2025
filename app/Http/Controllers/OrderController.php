<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    // Show checkout page
    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $deliveryFee = 5000;
        $total = $subtotal + $deliveryFee;

        return view('orders.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'total'));
    }

    // Process checkout and create order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|max:500',
            'payment_method' => 'required|in:QRIS,E-Wallet,Cash',
            'notes' => 'nullable|string|max:500',
        ]);

        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Ensure user has a customer record
        $customer = auth()->user()->customer;
        if (!$customer) {
            $customer = Customer::create([
                'user_id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]);
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $deliveryFee = 5000;
        $total = $subtotal + $deliveryFee;

        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $total,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create order items from cart
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price,
                'subtotal' => $cartItem->product->price * $cartItem->quantity,
            ]);
        }

        // Clear cart
        auth()->user()->cartItems()->delete();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }

    // List user's orders
    public function index()
    {
        $customer = auth()->user()->customer;
        
        if (!$customer) {
            $orders = collect();
        } else {
            $orders = Order::where('customer_id', $customer->id)
                ->with('orderItems.product')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('orders.index', compact('orders'));
    }

    // Show single order details
    public function show(Order $order)
    {
        $this->authorize('own', $order);
        
        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }

    // Cancel order
    public function cancel(Order $order)
    {
        $this->authorize('own', $order);

        if (!in_array($order->order_status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled');
        }

        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order cancelled successfully');
    }
}
