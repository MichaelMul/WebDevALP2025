@extends('layouts.app')

@section('title', 'Menu - Kaya Boys')

@section('content')
@php
    $primary = '#D97706';
    $popularProducts = ['Classic Club', 'BBQ Chicken', 'Philly Cheesesteak'];
@endphp

<!-- Menu Header -->
<div style="background: #FFFBF0; padding: 3rem 2rem; text-align: center;">
    <h1 style="font-size: 2.5rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.5rem;">Our Menu</h1>
    <p style="color: #666; font-size: 1.1rem;">Discover our complete selection of fresh, handcrafted sandwiches</p>
</div>

<!-- Menu Grid -->
<div style="background: #FFFBF0; padding: 3rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 2rem;">
            @foreach ($products as $product)
                @php
                    $isDark = in_array($product->name, ['Classic Club', 'Italian Sub', 'BBQ Chicken', 'Spicy Tuna']);
                    $isPopular = in_array($product->name, $popularProducts);
                @endphp
                <div class="product-card" style="background: {{ $isDark ? '#1a1a1a' : '#ffffff' }}; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.12); position: relative;">
                    <div class="product-image-container" style="height: 200px; position: relative;">
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="product-image" style="width: 100%; height: 100%; object-fit: cover;">
                        @if ($isPopular)
                            <div class="product-badge" style="background: {{ $primary }}; color: white; position: absolute; top: 12px; right: 12px; padding: 0.35rem 0.75rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem;">Popular</div>
                        @endif
                    </div>

                    <div class="product-info" style="padding: 1.5rem; background: white;">
                        <div class="product-name" style="color: #1a1a1a; font-weight: 700; margin-bottom: 0.5rem;">{{ $product->name }}</div>
                        <div class="product-description" style="color: #666; font-size: 0.95rem; margin-bottom: 1rem; line-height: 1.4;">{{ $product->description }}</div>

                        <div class="product-footer" style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="product-price" style="color: {{ $primary }}; font-weight: 800; font-size: 1.2rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="product-rating" style="display: flex; gap: 0.35rem; align-items: center; color: #666; font-weight: 600;">
                                <span class="star" style="color: #FFB800;">★</span>
                                <span style="font-size: 0.95rem;">{{ number_format(4.5 + ($product->id * 0.05), 1) }}</span>
                            </div>
                        </div>

                        @auth
                            <form action="{{ route('cart.add', $product) }}" method="POST" style="margin-top: 1rem;">
                                @csrf
                                <button type="submit" class="add-to-cart-btn" style="width: 100%; background: transparent; color: {{ $primary }}; border: 1px solid {{ $primary }}; padding: 0.65rem; cursor: pointer; font-weight: 700; border-radius: 8px;">+ Add to Cart</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" style="display: block; text-align: center; width: 100%; background: transparent; color: {{ $primary }}; border: 1px solid {{ $primary }}; padding: 0.65rem; cursor: pointer; font-weight: 700; border-radius: 8px; text-decoration: none; margin-top: 1rem;">Login to Order</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Back to Home -->
<div style="background: white; padding: 2rem; text-align: center;">
    <a href="{{ url('/') }}" style="display: inline-block; background: {{ $primary }}; color: white; padding: 0.85rem 2rem; border-radius: 10px; text-decoration: none; font-weight: 700;">← Back to Home</a>
</div>

@endsection
