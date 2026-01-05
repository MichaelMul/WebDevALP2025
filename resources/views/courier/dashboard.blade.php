@extends('layouts.app')

@section('title', 'Driver Dashboard - Kaya Boys')

@section('head')
    <style>
        .delivery-card {
            transition: all 0.3s ease;
        }
        .delivery-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
<div style="background: #F3F4F6; min-height: 100vh; padding: 2rem 1rem;">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        {{-- Header with Online/Offline Toggle --}}
        <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 16px; padding: 2rem; margin-bottom: 2rem; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">👋 Hi, {{ auth()->user()->name }}!</h1>
                    <p style="opacity: 0.9; font-size: 1.1rem;">
                        Status: <strong>{{ $courier->is_available ? '🟢 Online' : '🔴 Offline' }}</strong>
                    </p>
                </div>
                <form action="{{ route('courier.toggle-availability') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: white; color: #059669; padding: 1rem 2rem; border: none; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        {{ $courier->is_available ? '📴 Go Offline' : '📶 Go Online' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div style="background: #D1FAE5; border-left: 4px solid #10B981; color: #065F46; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #FEE2E2; border-left: 4px solid #EF4444; color: #7F1D1D; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- Stats Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Total Deliveries</div>
                <div style="font-size: 2rem; font-weight: 700; color: #1F2937;">{{ $stats['total_deliveries'] }}</div>
            </div>
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Rating</div>
                <div style="font-size: 2rem; font-weight: 700; color: #F59E0B;">⭐ {{ number_format($stats['rating'], 1) }}</div>
            </div>
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Active Deliveries</div>
                <div style="font-size: 2rem; font-weight: 700; color: #10B981;">{{ $stats['active_count'] }}</div>
            </div>
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Completed Today</div>
                <div style="font-size: 2rem; font-weight: 700; color: #8B5CF6;">{{ $stats['completed_today'] }}</div>
            </div>
        </div>

        {{-- Active Deliveries --}}
        @if($activeDeliveries->count() > 0)
        <div style="background: white; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1F2937;">� Active Deliveries</h2>
            @foreach($activeDeliveries as $delivery)
            <div class="delivery-card" style="border: 2px solid #10B981; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; background: #F0FDF4;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 700; font-size: 1.1rem; color: #1F2937; margin-bottom: 0.5rem;">
                            Order #{{ $delivery->order->order_number }}
                        </div>
                        <div style="color: #6B7280; font-size: 0.9rem;">
                             {{ Str::limit($delivery->order->delivery_address, 50) }}
                        </div>
                        <div style="color: #6B7280; font-size: 0.9rem; margin-top: 0.25rem;">
                            💰 Rp {{ number_format($delivery->order->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                    <span style="background: #10B981; color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem;">
                        {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                    </span>
                </div>

                {{-- Map Section --}}
                @if($delivery->order->delivery_latitude && $delivery->order->delivery_longitude)
                <div class="map-container" id="map-delivery-{{ $delivery->id }}" style="margin-bottom: 1rem;"></div>
                @else
                <div style="background: #FEF3C7; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; color: #92400E; font-size: 0.9rem;">
                    ⚠️ Location coordinates not available. Address: {{ $delivery->order->delivery_address }}
                </div>
                @endif

                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    @if($delivery->status === 'picked_up')
                        <form action="{{ route('courier.update-status', $delivery) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="on_the_way">
                            <button type="submit" style="background: #3B82F6; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                🏍️ Start Delivery
                            </button>
                        </form>
                    @endif
                    @if($delivery->status === 'on_the_way')
                        <form action="{{ route('courier.update-status', $delivery) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" style="background: #10B981; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                ✅ Mark as Delivered
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Available Orders --}}
        <div style="background: white; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1F2937;">📦 Available Orders</h2>
            
            @if($availableOrders->count() > 0)
                @foreach($availableOrders as $order)
                <div style="border: 1px solid #E5E7EB; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; transition: all 0.3s;" onmouseover="this.style.borderColor='#D97706'" onmouseout="this.style.borderColor='#E5E7EB'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 1.1rem; color: #1F2937; margin-bottom: 0.5rem;">
                                Order #{{ $order->order_number }}
                            </div>
                            <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                👤 {{ $order->customer->user->name }}
                            </div>
                            <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                📍 {{ Str::limit($order->delivery_address, 60) }}
                            </div>
                            <div style="color: #D97706; font-size: 1rem; font-weight: 700; margin-top: 0.5rem;">
                                💰 Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                        <form action="{{ route('courier.accept-order', $order) }}" method="POST">
                            @csrf
                            <button type="submit" style="background: #D97706; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                🚀 Accept Order
                            </button>
                        </form>
                    </div>

                    <div style="border-top: 1px solid #E5E7EB; padding-top: 1rem; margin-top: 1rem;">
                        <div style="font-size: 0.85rem; color: #6B7280; margin-bottom: 0.5rem;">Order Items:</div>
                        @foreach($order->orderItems as $item)
                            <div style="font-size: 0.9rem; color: #374151;">• {{ $item->quantity }}x {{ $item->product->name }}</div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 3rem; color: #9CA3AF;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                    <p style="font-size: 1.1rem;">No available orders at the moment</p>
                    <p style="font-size: 0.9rem; margin-top: 0.5rem;">Check back later for new delivery opportunities!</p>
                </div>
            @endif
        </div>

        {{-- Recent Completed Deliveries --}}
        @if($completedDeliveries->count() > 0)
        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1F2937;">✅ Recent Completed Deliveries</h2>
            @foreach($completedDeliveries as $delivery)
            <div style="border-bottom: 1px solid #E5E7EB; padding: 1rem 0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: #1F2937;">Order #{{ $delivery->order->order_number }}</div>
                        <div style="color: #6B7280; font-size: 0.85rem;">
                            @if($delivery->delivery_time)
                                {{ $delivery->delivery_time->diffForHumans() }}
                            @else
                                Recently delivered
                            @endif
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: #10B981; font-weight: 600;">Delivered</div>
                        <div style="color: #6B7280; font-size: 0.85rem;">Rp {{ number_format($delivery->order->total_amount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection