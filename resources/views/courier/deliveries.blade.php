@extends('layouts.app')

@section('title', 'My Deliveries - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="font-size: 2.5rem; color: #1a1a1a;">My Deliveries</h1>
        </div>

        @if(session('success'))
        <div style="background: #4CAF50; color: white; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
        @endif

        @if($deliveries->isEmpty())
        <div style="background: white; border-radius: 12px; padding: 3rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: #666;">No Deliveries Yet</h2>
            <p style="color: #999; margin-bottom: 2rem;">You haven't accepted any orders yet. Go to your dashboard to find available orders.</p>
            <a href="{{ route('courier.dashboard') }}" style="background: #D97706; color: white; padding: 0.8rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">View Available Orders</a>
        </div>
        @else
        <div style="display: grid; gap: 1.5rem;">
            @foreach($deliveries as $delivery)
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <!-- Order Header -->
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #F0F0F0;">
                    <!-- Order Info -->
                    <div>
                        <div style="font-size: 0.85rem; color: #999; margin-bottom: 0.3rem;">Order</div>
                        <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 1rem;">{{ $delivery->order->order_number }}</div>
                        
                        <div style="color: #666; font-size: 0.9rem;">
                            <div style="margin-bottom: 0.5rem;">
                                <strong>Customer:</strong> {{ $delivery->order->customer->user->name }}
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <strong>Phone:</strong> {{ $delivery->order->customer->user->phone ?? 'Not provided' }}
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <strong>Address:</strong> {{ $delivery->order->delivery_address }}
                            </div>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div>
                        <div style="font-size: 0.85rem; color: #999; margin-bottom: 0.3rem;">Total Amount</div>
                        <div style="font-weight: 700; font-size: 1.3rem; color: #D97706;">Rp {{ number_format($delivery->order->total_amount, 0, ',', '.') }}</div>
                    </div>

                    <!-- Order Time -->
                    <div>
                        <div style="font-size: 0.85rem; color: #999; margin-bottom: 0.3rem;">Ordered</div>
                        <div style="font-weight: 600; font-size: 0.95rem;">{{ $delivery->order->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>

                <!-- Order Items -->
                <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #F0F0F0;">
                    <div style="font-weight: 700; margin-bottom: 0.8rem; color: #1a1a1a;">Items:</div>
                    <div style="display: grid; gap: 0.5rem;">
                        @foreach($delivery->order->orderItems as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background: #FFFBF0; border-radius: 6px;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">{{ $item->product->name }}</div>
                                <div style="color: #666; font-size: 0.85rem;">Qty: {{ $item->quantity }}</div>
                            </div>
                            <div style="text-align: right; font-weight: 600;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Status & Actions -->
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: center;">
                    <!-- Current Status -->
                    <div>
                        <div style="font-size: 0.85rem; color: #999; margin-bottom: 0.3rem;">Current Status</div>
                        <div style="display: inline-block; padding: 0.4rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.9rem;
                            @if($delivery->status === 'pending')
                                background: #FEF3C7; color: #92400E;
                            @elseif($delivery->status === 'picked_up')
                                background: #DBEAFE; color: #1E40AF;
                            @elseif($delivery->status === 'in_transit')
                                background: #E0E7FF; color: #3730A3;
                            @elseif($delivery->status === 'delivered')
                                background: #DCFCE7; color: #166534;
                            @endif
                        ">
                            @if($delivery->status === 'pending')
                                ⏳ Food Being Prepared
                            @elseif($delivery->status === 'picked_up')
                                📦 Food Picked Up
                            @elseif($delivery->status === 'in_transit')
                                🚚 On My Way
                            @elseif($delivery->status === 'delivered')
                                ✅ Delivered
                            @endif
                        </div>
                    </div>

                    <!-- Status Update Buttons -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 0.5rem;">
                        @if($delivery->status === 'delivered')
                            <div style="padding: 0.7rem; background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; border-radius: 6px; font-weight: 600; text-align: center; font-size: 0.9rem;">
                                🔒 Delivery Complete
                            </div>
                        @else
                            @if($delivery->status !== 'picked_up')
                            <form action="{{ route('courier.update-status', $delivery) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="status" value="picked_up">
                                <button type="submit" style="width: 100%; padding: 0.7rem; background: #DBEAFE; color: #1E40AF; border: 1px solid #BFDBFE; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem;">📦 Picked Up</button>
                            </form>
                            @endif

                            @if($delivery->status !== 'in_transit')
                            <form action="{{ route('courier.update-status', $delivery) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="status" value="in_transit">
                                <button type="submit" style="width: 100%; padding: 0.7rem; background: #E0E7FF; color: #3730A3; border: 1px solid #C7D2FE; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem;">🚚 On My Way</button>
                            </form>
                            @endif

                            <form action="{{ route('courier.update-status', $delivery) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" style="width: 100%; padding: 0.7rem; background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem;">✅ Mark as Delivered</button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                @if($delivery->pickup_time || $delivery->delivery_time)
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #F0F0F0;">
                    <div style="font-weight: 700; margin-bottom: 0.8rem; color: #1a1a1a;">Timeline:</div>
                    <div style="display: grid; gap: 0.5rem; font-size: 0.9rem; color: #666;">
                        @if($delivery->pickup_time)
                        <div>📦 Picked up: <strong>{{ $delivery->pickup_time->format('d M Y H:i') }}</strong></div>
                        @endif
                        @if($delivery->delivery_time)
                        <div>✅ Delivered: <strong>{{ $delivery->delivery_time->format('d M Y H:i') }}</strong></div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<style>
    button:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endsection
