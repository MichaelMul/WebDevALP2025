@extends('layouts.app')

@section('title', 'Shopping Cart - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: #1a1a1a;">Shopping Cart</h1>

        @if($cartItems->count() > 0)
            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; margin-bottom: 2rem;">
                <!-- Cart Items -->
                <div style="background: white; border-radius: 12px; padding: 2rem;">
                    @foreach($cartItems as $item)
                    <div style="display: flex; gap: 1rem; padding-bottom: 2rem; border-bottom: 1px solid #F0F0F0;">
                        <img src="{{ $item->product->image_url ?? asset('images/products/sandwich.jpg') }}" alt="{{ $item->product->name }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                        
                        <div style="flex: 1;">
                            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $item->product->name }}</h3>
                            <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">{{ $item->product->description }}</p>
                            
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <span style="color: #D97706; font-weight: 700; font-size: 1.1rem;">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                
                                <form action="{{ route('cart.update', $item) }}" method="POST" style="display: flex; gap: 0.5rem;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" style="width: 60px; padding: 0.4rem; border: 1px solid #D97706; border-radius: 6px;">
                                    <button type="submit" style="background: #D97706; color: white; border: none; padding: 0.4rem 1rem; border-radius: 6px; cursor: pointer;">Update</button>
                                </form>

                                <form action="{{ route('cart.remove', $item) }}" method="POST" style="margin-left: auto;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #FF6B6B; color: white; border: none; padding: 0.4rem 1rem; border-radius: 6px; cursor: pointer;">Remove</button>
                                </form>
                            </div>
                        </div>

                        <div style="text-align: right; padding-top: 0.5rem;">
                            <div style="color: #D97706; font-weight: 700; font-size: 1.1rem;">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div style="background: white; border-radius: 12px; padding: 2rem; height: fit-content;">
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem;">Order Summary</h3>
                    
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #F0F0F0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #666;">Subtotal</span>
                            <span style="font-weight: 600;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #666;">Delivery Fee</span>
                            <span style="font-weight: 600;">Rp 5.000</span>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 1.2rem;">
                        <span style="font-weight: 700;">Total</span>
                        <span style="color: #D97706; font-weight: 700;">Rp {{ number_format($total + 5000, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout') }}" style="display: block; width: 100%; background: #D97706; color: white; border: none; padding: 0.9rem; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-bottom: 1rem; text-align: center; text-decoration: none;">Proceed to Checkout</a>
                    
                    <a href="{{ route('menu') }}" style="display: block; text-align: center; background: transparent; color: #D97706; border: 1px solid #D97706; padding: 0.9rem; border-radius: 8px; font-weight: 600; text-decoration: none;">Continue Shopping</a>
                </div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 2rem;">
                <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: #FF6B6B; color: white; border: none; padding: 0.6rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Clear Cart</button>
                </form>
            </div>
        @else
            <div style="background: white; border-radius: 12px; padding: 4rem 2rem; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Your cart is empty</h2>
                <p style="color: #666; margin-bottom: 2rem;">Start adding delicious sandwiches to your cart!</p>
                <a href="{{ route('menu') }}" style="display: inline-block; background: #D97706; color: white; padding: 0.9rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">Browse Menu</a>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<div style="position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem 2rem; border-radius: 8px; z-index: 1000;">
    {{ session('success') }}
</div>
@endif

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 300px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
