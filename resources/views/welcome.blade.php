@extends('layouts.app')

@section('title', 'Kaya Boys - Fresh Sandwiches Delivered')

@section('content')
<!-- Hero Section -->
<div class="hero" style="background: linear-gradient(to bottom, #FFFBF0 0%, #FFFBF0 100%);">
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-badge" style="background: #FFF3E0;">🚚 Free delivery on orders over $20</div>
            <h1 style="font-size: 3.5rem; line-height: 1.1; margin: 2rem 0;">Crafted Sandwiches, Delivered Fresh</h1>
            <p style="font-size: 1.1rem; color: #666; margin-bottom: 2rem;">Order your favorite artisan sandwiches made with premium ingredients. Track your order in real-time and enjoy fast delivery.</p>
            
            <div class="hero-stats" style="margin-bottom: 2rem;">
                <div class="stat" style="background: #FFF9E6; padding: 1.2rem; border-radius: 8px; border: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.2rem;">⏱️ <span style="font-weight: 700; color: #333;">15-30</span></div>
                    <div style="font-size: 0.9rem; color: #666; margin-top: 0.5rem;">Mins Delivery</div>
                </div>
                <div class="stat" style="background: #FFF9E6; padding: 1.2rem; border-radius: 8px; border: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.2rem;">👥 <span style="font-weight: 700; color: #333;">1000+</span></div>
                    <div style="font-size: 0.9rem; color: #666; margin-top: 0.5rem;">Happy Customers</div>
                </div>
            </div>

            <div class="cta-buttons">
                <button class="btn btn-primary" onclick="document.getElementById('menu').scrollIntoView({behavior: 'smooth'})" style="background: #D97706; padding: 0.9rem 2rem; font-size: 1rem;">Browse Menu</button>
                <button class="btn btn-secondary" onclick="document.getElementById('why').scrollIntoView({behavior: 'smooth'})" style="color: #D97706; border-color: #D97706; padding: 0.9rem 2rem; font-size: 1rem;">Track Order</button>
            </div>
        </div>
        <div class="hero-image" style="text-align: right;">
            <img src="{{ asset('images/products/sandwich.jpg') }}" alt="Fresh sandwich" style="max-width: 100%; height: auto; border-radius: 16px;">
        </div>
    </div>
</div>

<!-- Featured Sandwiches -->
<div class="section" id="menu" style="background: #FFFBF0; padding: 4rem 2rem;">
    <h2 class="section-title" style="margin-bottom: 0.5rem;">Featured Sandwiches</h2>
    <p style="text-align: center; color: #666; font-size: 1rem; margin-bottom: 3rem;">Handcrafted with love, delivered with care</p>
    
    <div class="products-grid">
        <!-- Classic Club -->
        <div class="product-card" style="background: #1a1a1a; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="product-image-container" style="height: 240px;">
                <img src="{{ asset('images/products/sandwich.jpg') }}" alt="Classic Club" class="product-image" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="product-badge" style="background: #D97706; color: white; position: absolute; top: 12px; right: 12px;">Popular</div>
            </div>
            <div class="product-info" style="padding: 1.5rem; background: white;">
                <div class="product-name" style="color: #1a1a1a; font-weight: 700; margin-bottom: 0.5rem;">Classic Club</div>
                <div class="product-description" style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Turkey, bacon, lettuce, tomato, mayo on toasted bread</div>
                    <div class="product-footer" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="product-price" style="color: #D97706; font-weight: 700; font-size: 1.2rem;">Rp 23.000</div>
                    <div class="product-rating" style="display: flex; gap: 0.3rem; align-items: center;">
                        <span class="star" style="color: #FFB800;">★</span>
                        <span style="font-size: 0.9rem; color: #666;">4.0</span>
                    </div>
                </div>
                @auth
                <form action="{{ route('cart.add', 1) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <button type="submit" class="add-to-cart-btn" style="width: 100%; background: transparent; color: #D97706; border: 1px solid #D97706; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px;">+ Add to Cart</button>
                </form>
                @else
                <a href="{{ route('login') }}" style="display: block; text-align: center; width: 100%; background: transparent; color: #D97706; border: 1px solid #D97706; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px; text-decoration: none;">Login to Order</a>
                @endauth
            </div>
        </div>

        <!-- Veggie Delight -->
        <div class="product-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div class="product-image-container" style="height: 240px;">
                <img src="{{ asset('images/products/sandwich.jpg') }}" alt="Veggie Delight" class="product-image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="product-info" style="padding: 1.5rem; background: white;">
                <div class="product-name" style="color: #1a1a1a; font-weight: 700; margin-bottom: 0.5rem;">Veggie Delight</div>
                <div class="product-description" style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Fresh vegetables, hummus, sprouts on whole grain</div>
                <div class="product-footer" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="product-price" style="color: #D97706; font-weight: 700; font-size: 1.2rem;">Rp 20.000</div>
                    <div class="product-rating" style="display: flex; gap: 0.3rem; align-items: center;">
                        <span class="star" style="color: #FFB800;">★</span>
                        <span style="font-size: 0.9rem; color: #666;">4.6</span>
                    </div>
                </div>
                @auth
                <form action="{{ route('cart.add', 2) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <button type="submit" class="add-to-cart-btn" style="width: 100%; background: #FFE5CC; color: #D97706; border: none; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px;">+ Add to Cart</button>
                </form>
                @else
                <a href="{{ route('login') }}" style="display: block; text-align: center; width: 100%; margin-top: 1rem; background: #FFE5CC; color: #D97706; border: none; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px; text-decoration: none;">Login to Order</a>
                @endauth
            </div>
        </div>

        <!-- BBQ Chicken -->
        <div class="product-card" style="background: #1a1a1a; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="product-image-container" style="height: 240px;">
                <img src="{{ asset('images/products/sandwich.jpg') }}" alt="BBQ Chicken" class="product-image" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="product-badge" style="background: #D97706; color: white; position: absolute; top: 12px; right: 12px;">Popular</div>
            </div>
            <div class="product-info" style="padding: 1.5rem; background: white;">
                <div class="product-name" style="color: #1a1a1a; font-weight: 700; margin-bottom: 0.5rem;">BBQ Chicken</div>
                <div class="product-description" style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Grilled chicken, BBQ sauce, cheese, onions</div>
                <div class="product-footer" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="product-price" style="color: #D97706; font-weight: 700; font-size: 1.2rem;">Rp 25.000</div>
                    <div class="product-rating" style="display: flex; gap: 0.3rem; align-items: center;">
                        <span class="star" style="color: #FFB800;">★</span>
                        <span style="font-size: 0.9rem; color: #666;">4.9</span>
                    </div>
                </div>
                @auth
                <form action="{{ route('cart.add', 3) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <button type="submit" class="add-to-cart-btn" style="width: 100%; background: transparent; color: #D97706; border: 1px solid #D97706; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px;">+ Add to Cart</button>
                </form>
                @else
                <a href="{{ route('login') }}" style="display: block; text-align: center; width: 100%; background: transparent; color: #D97706; border: 1px solid #D97706; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px; text-decoration: none;">Login to Order</a>
                @endauth
            </div>
        </div>

        <!-- Italian Sub -->
        <div class="product-card" style="background: #1a1a1a; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="product-image-container" style="height: 240px;">
                <img src="{{ asset('images/products/sandwich.jpg') }}" alt="Italian Sub" class="product-image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="product-info" style="padding: 1.5rem; background: white;">
                <div class="product-name" style="color: #1a1a1a; font-weight: 700; margin-bottom: 0.5rem;">Italian Sub</div>
                <div class="product-description" style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Salami, pepperoni, ham, provolone, Italian dressing</div>
                <div class="product-footer" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="product-price" style="color: #D97706; font-weight: 700; font-size: 1.2rem;">Rp 26.000</div>
                    <div class="product-rating" style="display: flex; gap: 0.3rem; align-items: center;">
                        <span class="star" style="color: #FFB800;">★</span>
                        <span style="font-size: 0.9rem; color: #666;">4.7</span>
                    </div>
                </div>
                @auth
                <form action="{{ route('cart.add', 4) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <button type="submit" class="add-to-cart-btn" style="width: 100%; background: transparent; color: #D97706; border: 1px solid #D97706; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px;">+ Add to Cart</button>
                </form>
                @else
                <a href="{{ route('login') }}" style="display: block; text-align: center; width: 100%; background: transparent; color: #D97706; border: 1px solid #D97706; padding: 0.6rem; cursor: pointer; font-weight: 600; border-radius: 6px; text-decoration: none;">Login to Order</a>
                @endauth
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 3rem;">
        <a href="{{ route('menu') }}" style="display: inline-block; background: transparent; color: #D97706; border: 2px solid #D97706; padding: 0.8rem 2rem; font-weight: 600; border-radius: 8px; text-decoration: none; font-size: 1rem;">View Full Menu</a>
    </div>
</div>

<!-- Why Choose Us -->
<div class="section" id="why" style="background: #FFFBF0; padding: 4rem 2rem;">
    <h2 class="section-title" style="margin-bottom: 1rem;">Why Choose Us</h2>
    <p style="text-align: center; color: #666; font-size: 1rem; margin-bottom: 3rem;">We make ordering sandwiches easy, fast, and convenient with modern features</p>
    
    <div class="features-grid">
        <div class="feature-card" style="background: white; border: 1px solid #F0F0F0; border-radius: 12px; padding: 2rem; text-align: center;">
            <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 1rem;">📍</div>
            <div class="feature-title" style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Real-Time Tracking</div>
            <div class="feature-description" style="color: #666; font-size: 0.95rem;">Track your order from kitchen to doorstep with live updates, just like Gojek.</div>
        </div>

        <div class="feature-card" style="background: white; border: 1px solid #F0F0F0; border-radius: 12px; padding: 2rem; text-align: center;">
            <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 1rem;">📱</div>
            <div class="feature-title" style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">QRIS Payment</div>
            <div class="feature-description" style="color: #666; font-size: 0.95rem;">Pay instantly with QRIS or choose Cash on Delivery for your convenience.</div>
        </div>

        <div class="feature-card" style="background: white; border: 1px solid #F0F0F0; border-radius: 12px; padding: 2rem; text-align: center;">
            <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 1rem;">💳</div>
            <div class="feature-title" style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">E-Wallet System</div>
            <div class="feature-description" style="color: #666; font-size: 0.95rem;">Top up your wallet for faster checkout and exclusive member rewards.</div>
        </div>

        <div class="feature-card" style="background: white; border: 1px solid #F0F0F0; border-radius: 12px; padding: 2rem; text-align: center;">
            <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 1rem;">👤</div>
            <div class="feature-title" style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Membership Benefits</div>
            <div class="feature-description" style="color: #666; font-size: 0.95rem;">Join as a member or checkout as guest. Members get special discounts and perks.</div>
        </div>

        <div class="feature-card" style="background: white; border: 1px solid #F0F0F0; border-radius: 12px; padding: 2rem; text-align: center;">
            <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 1rem;">❌</div>
            <div class="feature-title" style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Easy Cancellation</div>
            <div class="feature-description" style="color: #666; font-size: 0.95rem;">Changed your mind? Cancel your order with just a tap before preparation starts.</div>
        </div>

        <div class="feature-card" style="background: white; border: 1px solid #F0F0F0; border-radius: 12px; padding: 2rem; text-align: center;">
            <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 1rem;">💳</div>
            <div class="feature-title" style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Multiple Payment Options</div>
            <div class="feature-description" style="color: #666; font-size: 0.95rem;">Choose from QRIS, e-wallet, cash on delivery, or credit card payment.</div>
        </div>
    </div>
</div>

<!-- How It Works -->
<div class="section" id="how" style="background: white; padding: 4rem 2rem;">
    <h2 class="section-title" style="margin-bottom: 1rem;">How It Works</h2>
    <p style="text-align: center; color: #666; font-size: 1rem; margin-bottom: 3rem;">Ordering from Kaya Boys is as simple as 1-2-3-4</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="text-align: center; position: relative;">
            <div style="width: 80px; height: 80px; background: #FFB627; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.8rem; font-weight: 700; color: white;">1</div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Browse Menu</div>
            <div style="color: #666; font-size: 0.95rem;">Check out our delicious sandwich collection</div>
        </div>

        <div style="text-align: center; position: relative;">
            <div style="width: 80px; height: 80px; background: #FFB627; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.8rem; font-weight: 700; color: white;">2</div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Place Order</div>
            <div style="color: #666; font-size: 0.95rem;">Add items to cart and checkout with your preferred payment</div>
        </div>

        <div style="text-align: center; position: relative;">
            <div style="width: 80px; height: 80px; background: #FFB627; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.8rem; font-weight: 700; color: white;">3</div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Track Delivery</div>
            <div style="color: #666; font-size: 0.95rem;">See your order status and courier location in real-time</div>
        </div>

        <div style="text-align: center; position: relative;">
            <div style="width: 80px; height: 80px; background: #FFB627; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.8rem; font-weight: 700; color: white;">4</div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Enjoy Meal</div>
            <div style="color: #666; font-size: 0.95rem;">Receive your fresh sandwich and enjoy your meal</div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div style="background: #FFB627; padding: 4rem 2rem; text-align: center;">
    <h2 style="font-size: 2.2rem; font-weight: 700; color: white; margin-bottom: 2rem;">Ready to Order Your Perfect Sandwich?</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="background: rgba(255, 255, 255, 0.2); padding: 1.5rem; border-radius: 12px;">
            <div style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 0.5rem;">50K+</div>
            <div style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem;">Orders Delivered</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.2); padding: 1.5rem; border-radius: 12px;">
            <div style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 0.5rem;">4.8★</div>
            <div style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem;">Average Rating</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.2); padding: 1.5rem; border-radius: 12px;">
            <div style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 0.5rem;">15min</div>
            <div style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem;">Avg Delivery Time</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.2); padding: 1.5rem; border-radius: 12px;">
            <div style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 0.5rem;">24/7</div>
            <div style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem;">Customer Support</div>
        </div>
    </div>

    <button style="margin-top: 2rem; background: white; color: #FFB627; border: none; padding: 0.9rem 2.5rem; font-size: 1rem; font-weight: 700; border-radius: 8px; cursor: pointer;">Get Started</button>
</div>
@endsection
