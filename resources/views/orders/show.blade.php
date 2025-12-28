@extends('layouts.app')

@section('title', 'Order Details - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('orders.index') }}" style="color: #D97706; text-decoration: none; font-weight: 600;">← Back to Orders</a>
        </div>

        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $order->order_number }}</h1>
                    <p style="color: #666;">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                
                @php
                    $statusColors = [
                        'pending' => '#F59E0B',
                        'confirmed' => '#3B82F6',
                        'preparing' => '#8B5CF6',
                        'delivering' => '#06B6D4',
                        'completed' => '#10B981',
                        'cancelled' => '#EF4444'
                    ];
                    $statusColor = $statusColors[$order->order_status] ?? '#6B7280';
                @endphp
                <span style="background: {{ $statusColor }}; color: white; padding: 0.6rem 1.5rem; border-radius: 20px; font-size: 1rem; font-weight: 600; text-transform: capitalize;">
                    {{ $order->order_status }}
                </span>
            </div>

            <!-- Order Items -->
            <div style="border-top: 1px solid #F0F0F0; padding-top: 1.5rem; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">Order Items</h2>
                
                @foreach($order->orderItems as $item)
                <div style="display: flex; gap: 1rem; padding: 1rem; background: #FFFBF0; border-radius: 8px; margin-bottom: 1rem;">
                    <img src="{{ $item->product->image_url ?? asset('images/products/sandwich.jpg') }}" alt="{{ $item->product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px;">
                    
                    <div style="flex: 1;">
                        <h3 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.3rem;">{{ $item->product->name }}</h3>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 0.5rem;">{{ $item->product->description }}</p>
                        <div style="color: #666;">Quantity: {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                    </div>

                    <div style="text-align: right; padding-top: 0.5rem;">
                        <div style="color: #D97706; font-weight: 700; font-size: 1.1rem;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Delivery Information -->
            <div style="border-top: 1px solid #F0F0F0; padding-top: 1.5rem; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">Delivery Information</h2>
                <div style="background: #FFFBF0; padding: 1rem; border-radius: 8px;">
                    <div style="margin-bottom: 0.5rem;"><strong>Address:</strong></div>
                    <div style="color: #666; margin-bottom: 1rem;">{{ $order->delivery_address }}</div>
                    
                    @if($order->notes)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #F0E5C9;">
                        <div style="margin-bottom: 0.5rem;"><strong>Notes:</strong></div>
                        <div style="color: #666;">{{ $order->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment & Summary -->
            <div style="border-top: 1px solid #F0F0F0; padding-top: 1.5rem;">
                <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">Payment Details</h2>
                
                <div style="background: #FFFBF0; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span>Payment Method:</span>
                        <span style="font-weight: 600;">{{ $order->payment_method }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span>Payment Status:</span>
                        <span style="font-weight: 600; color: {{ $order->payment_status === 'paid' ? '#10B981' : '#F59E0B' }}; text-transform: capitalize;">
                            {{ $order->payment_status }}
                        </span>
                    </div>
                    
                    <div style="border-top: 1px solid #F0E5C9; margin-top: 1rem; padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #666;">Subtotal</span>
                            <span style="font-weight: 600;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="color: #666;">Delivery Fee</span>
                            <span style="font-weight: 600;">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.3rem;">
                            <span style="font-weight: 700;">Total</span>
                            <span style="color: #D97706; font-weight: 700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if(in_array($order->order_status, ['pending', 'confirmed']))
                <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    @csrf
                    <button type="submit" style="background: #FF6B6B; color: white; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancel Order</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div style="position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem 2rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="position: fixed; top: 20px; right: 20px; background: #FF6B6B; color: white; padding: 1rem 2rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    {{ session('error') }}
</div>
@endif
@endsection
