@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Edit Order #{{ $order->order_number }}</h2>
        </div>

        <!-- Form -->
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Customer Info (Read-only) -->
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f9fafb; border-radius: 4px;">
                    <div style="margin-bottom: 0.5rem;"><strong>Customer:</strong> {{ $order->customer->user->name }}</div>
                    <div style="margin-bottom: 0.5rem;"><strong>Email:</strong> {{ $order->customer->user->email }}</div>
                    <div><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </div>

                <!-- Order Status -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Order Status</label>
                    <select name="order_status" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">
                        <option value="pending" {{ old('order_status', $order->order_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('order_status', $order->order_status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="picked_up" {{ old('order_status', $order->order_status) === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                        <option value="in_transit" {{ old('order_status', $order->order_status) === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="delivered" {{ old('order_status', $order->order_status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ old('order_status', $order->order_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('order_status')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Status -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Payment Status</label>
                    <select name="payment_status" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">
                        <option value="pending" {{ old('payment_status', $order->payment_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('payment_status', $order->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ old('payment_status', $order->payment_status) === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ old('payment_status', $order->payment_status) === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="cancelled" {{ old('payment_status', $order->payment_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('payment_status')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Delivery Address -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Delivery Address</label>
                    <textarea name="delivery_address" rows="3" required
                              style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">{{ old('delivery_address', $order->delivery_address) }}</textarea>
                    @error('delivery_address')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Notes -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Notes</label>
                    <textarea name="notes" rows="2"
                              style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">{{ old('notes', $order->notes) }}</textarea>
                    @error('notes')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Order Items (Read-only) -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Order Items</label>
                    <div style="border: 1px solid #d1d5db; border-radius: 4px; padding: 1rem; background: #f9fafb;">
                        @foreach($order->orderItems as $item)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" 
                            style="flex: 1; background: #f59e0b; color: white; padding: 0.75rem; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        Update Order
                    </button>
                    <a href="{{ route('admin.orders.index') }}" 
                       style="flex: 1; background: #6b7280; color: white; padding: 0.75rem; border-radius: 4px; font-weight: 600; text-align: center; text-decoration: none; display: block;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
