@extends('layouts.app')

@section('title', 'My Orders - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: #1a1a1a;">My Orders</h1>

        @if($orders->count() > 0)
            <div style="display: grid; gap: 1.5rem;">
                @foreach($orders as $order)
                <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $order->order_number }}</h3>
                            <p style="color: #666; font-size: 0.95rem;">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div style="text-align: right;">
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
                            <span style="background: {{ $statusColor }}; color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; text-transform: capitalize;">
                                {{ $order->order_status }}
                            </span>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #F0F0F0; border-bottom: 1px solid #F0F0F0; padding: 1rem 0; margin-bottom: 1rem;">
                        @foreach($order->orderItems as $item)
                        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 0.8rem;">
                            <img src="{{ $item->product->image_url ?? asset('images/products/sandwich.jpg') }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">{{ $item->product->name }}</div>
                                <div style="color: #666; font-size: 0.9rem;">Qty: {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                            </div>
                            <div style="font-weight: 600; color: #D97706;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <div style="color: #666; font-size: 0.9rem; margin-bottom: 0.3rem;">Payment Method: <strong>{{ $order->payment_method }}</strong></div>
                            <div style="font-size: 1.2rem; font-weight: 700; color: #D97706;">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        </div>
                        
                        <a href="{{ route('orders.show', $order) }}" style="background: #D97706; color: white; padding: 0.7rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">View Details</a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div style="background: white; border-radius: 12px; padding: 4rem 2rem; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">No orders yet</h2>
                <p style="color: #666; margin-bottom: 2rem;">Start ordering delicious sandwiches!</p>
                <a href="{{ route('menu') }}" style="display: inline-block; background: #D97706; color: white; padding: 0.9rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">Browse Menu</a>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<div style="position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem 2rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    {{ session('success') }}
</div>
@endif
@endsection
