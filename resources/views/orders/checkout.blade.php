@extends('layouts.app')

@section('title', 'Checkout - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: #1a1a1a;">Checkout</h1>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
                <!-- Delivery Information -->
                <div style="background: white; border-radius: 12px; padding: 2rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Delivery Information</h2>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Delivery Address *</label>
                        <textarea name="delivery_address" rows="3" required 
                                  style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; resize: vertical;"
                                  placeholder="Enter your complete delivery address">{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <span style="color: #FF6B6B; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Notes (Optional)</label>
                        <textarea name="notes" rows="2" 
                                  style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; resize: vertical;"
                                  placeholder="Add any special instructions">{{ old('notes') }}</textarea>
                    </div>

                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; margin-top: 2rem;">Payment Method</h3>
                    
                    @php
                        $customer = auth()->user()->customer;
                        $walletBalance = $customer ? $customer->wallet_balance : 0;
                    @endphp
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <label style="display: flex; align-items: center; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="wallet" required style="margin-right: 1rem; width: 20px; height: 20px;" {{ $walletBalance < $total ? 'disabled' : '' }}>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 1.1rem;">Kaya Wallet</div>
                                <div style="color: #666; font-size: 0.9rem;">Balance: Rp {{ number_format($walletBalance, 0, ',', '.') }}</div>
                                @if($walletBalance < $total)
                                    <div style="color: #FF6B6B; font-size: 0.85rem; margin-top: 0.3rem;">Insufficient balance</div>
                                @endif
                            </div>
                        </label>
                        
                        <label style="display: flex; align-items: center; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="qris" style="margin-right: 1rem; width: 20px; height: 20px;">
                            <div>
                                <div style="font-weight: 600; font-size: 1.1rem;">QRIS</div>
                                <div style="color: #666; font-size: 0.9rem;">Scan QR code to pay</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="cash" style="margin-right: 1rem; width: 20px; height: 20px;">
                            <div>
                                <div style="font-weight: 600; font-size: 1.1rem;">Cash on Delivery</div>
                                <div style="color: #666; font-size: 0.9rem;">Pay when your order arrives</div>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <span style="color: #FF6B6B; font-size: 0.9rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Order Summary -->
                <div>
                    <div style="background: white; border-radius: 12px; padding: 2rem; position: sticky; top: 20px;">
                        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem;">Order Summary</h3>
                        
                        <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem;">
                            @foreach($cartItems as $item)
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #F0F0F0;">
                                <img src="{{ $item->product->image_url ?? asset('images/products/sandwich.jpg') }}" alt="{{ $item->product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; margin-bottom: 0.3rem;">{{ $item->product->name }}</div>
                                    <div style="color: #666; font-size: 0.9rem;">Qty: {{ $item->quantity }}</div>
                                    <div style="color: #D97706; font-weight: 600; margin-top: 0.3rem;">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #F0F0F0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="color: #666;">Subtotal</span>
                                <span style="font-weight: 600;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="color: #666;">Delivery Fee</span>
                                <span style="font-weight: 600;">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 1.2rem;">
                            <span style="font-weight: 700;">Total</span>
                            <span style="color: #D97706; font-weight: 700;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" style="width: 100%; background: #D97706; color: white; border: none; padding: 1rem; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-bottom: 1rem;">Place Order</button>
                        
                        <a href="{{ route('cart.index') }}" style="display: block; text-align: center; color: #D97706; text-decoration: none; font-weight: 600;">Back to Cart</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    input[type="radio"]:checked + div {
        color: #D97706;
    }
    
    label:has(input[type="radio"]:checked) {
        border-color: #D97706 !important;
        background-color: #FFF7ED;
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 350px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
