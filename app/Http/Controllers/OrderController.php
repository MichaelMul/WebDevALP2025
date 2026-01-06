<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Services\GeocodeService;
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
            'payment_method' => 'required|in:qris,cash,wallet',
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

        // Handle wallet payment
        if ($validated['payment_method'] === 'wallet') {
            if ($customer->wallet_balance < $total) {
                return redirect()->back()->with('error', 'Insufficient wallet balance. Please top up your wallet or choose another payment method.');
            }
            
            // Deduct from wallet balance
            \DB::transaction(function() use ($customer, $total) {
                $balanceBefore = $customer->wallet_balance;
                $customer->decrement('wallet_balance', $total);
                $customer->refresh();
                
                // Create wallet transaction record
                \App\Models\WalletTransaction::create([
                    'user_id' => auth()->id(),
                    'type' => 'debit',
                    'amount' => $total,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $customer->wallet_balance,
                    'description' => 'Order payment',
                ]);
            });
        }
        
        // Geocode delivery address to get coordinates
        $coordinates = GeocodeService::geocodeAddress($validated['delivery_address']);

        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $total,
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_method'] === 'wallet' ? 'paid' : 'pending',
            'order_status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
            'delivery_latitude' => $coordinates['latitude'] ?? null,
            'delivery_longitude' => $coordinates['longitude'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create order items from cart
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
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

        // Refund to wallet if payment was made via wallet
        if ($order->payment_method === 'wallet' && $order->payment_status === 'paid') {
            $customer = $order->customer;
            
            \DB::transaction(function() use ($customer, $order) {
                $balanceBefore = $customer->wallet_balance;
                $customer->increment('wallet_balance', $order->total_amount);
                $customer->refresh();
                
                // Create wallet transaction record for refund
                \App\Models\WalletTransaction::create([
                    'user_id' => auth()->id(),
                    'type' => 'credit',
                    'amount' => $order->total_amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $customer->wallet_balance,
                    'description' => 'Order cancellation refund - ' . $order->order_number,
                    'reference_id' => $order->id,
                ]);
            });
        }

        // Determine payment status based on refund
        $paymentStatus = ($order->payment_method === 'wallet' && $order->payment_status === 'paid') 
            ? 'refunded' 
            : 'pending';

        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => $paymentStatus,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order cancelled successfully' . ($order->payment_method === 'wallet' ? '. Refund has been credited to your wallet.' : ''));
    }

    // Rate courier
    public function rate(Request $request, Order $order)
    {
        $this->authorize('own', $order);

        // Validate that order is delivered
        if ($order->order_status !== 'delivered') {
            return redirect()->back()->with('error', 'You can only rate delivered orders');
        }

        // Validate that delivery exists
        if (!$order->delivery) {
            return redirect()->back()->with('error', 'No delivery record found');
        }

        // Validate that rating hasn't been submitted yet
        if ($order->delivery->customer_rating !== null) {
            return redirect()->back()->with('error', 'You have already rated this delivery');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        \DB::transaction(function() use ($order, $validated) {
            // Update delivery with rating
            $order->delivery->update([
                'customer_rating' => $validated['rating'],
                'customer_feedback' => $validated['feedback'] ?? null,
            ]);

            // Update courier's average rating
            $courier = $order->delivery->courier;
            
            $averageRating = \App\Models\Delivery::where('courier_id', $courier->id)
                ->whereNotNull('customer_rating')
                ->avg('customer_rating');

            $courier->update([
                'rating' => round($averageRating, 2),
            ]);
        });

        return redirect()->route('orders.show', $order)->with('success', 'Thank you for rating your courier!');
    }

    // Admin: Edit order
    public function edit(Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $order->load('orderItems.product', 'customer.user');
        return view('admin.orders.edit', compact('order'));
    }

    // Admin: Update order
    public function adminUpdate(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,picked_up,in_transit,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded,cancelled',
            'delivery_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully');
    }
}
