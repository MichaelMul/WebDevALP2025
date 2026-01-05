<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kaya Boys - Sandwich Delivery')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #E55A28;
            --secondary: #FFB627;
            --yellow-light: #FFC857;
            --text-dark: #1a1a1a;
            --text-light: #666666;
            --border: #f0f0f0;
            --rating: #FFB800;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-dark);
            background: #ffffff;
            line-height: 1.6;
        }

        /* Navigation */
        nav {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .cart-btn {
            position: relative;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .cart-btn:hover {
            background: var(--primary-dark);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--secondary);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: var(--text-dark);
            border-radius: 2px;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--yellow-light) 100%);
            padding: 4rem 2rem;
            text-align: center;
            color: var(--text-dark);
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat {
            background: rgba(255, 255, 255, 0.9);
            padding: 1rem;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
        }

        .hero-image {
            text-align: center;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        /* Section */
        .section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--text-dark);
        }

        /* Product Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .product-image-container {
            width: 100%;
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--secondary);
            color: var(--text-dark);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .product-description {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
        }

        .product-rating {
            display: flex;
            gap: 0.3rem;
            align-items: center;
        }

        .star {
            color: var(--rating);
            font-size: 0.9rem;
        }

        .add-to-cart-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .add-to-cart-btn:hover {
            background: var(--primary-dark);
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: #f9f9f9;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
        }

        .feature-card:hover {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .feature-description {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* How It Works */
        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            position: relative;
        }

        .step-card {
            background: white;
            border: 2px solid var(--border);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: var(--secondary);
            color: var(--text-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1rem;
        }

        .step-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .step-description {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--yellow-light) 100%);
            padding: 4rem 2rem;
            color: var(--text-dark);
        }

        .cta-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .cta-content p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: var(--text-dark);
        }

        .cta-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .cta-stat {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
        }

        .cta-stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .cta-stat-label {
            font-size: 1rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        /* Footer */
        footer {
            background: var(--text-dark);
            color: white;
            padding: 3rem 2rem 1rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--secondary);
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }

        .social-links a:hover {
            background: var(--secondary);
        }

        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 2rem;
            text-align: center;
            color: #999;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-container,
            .cta-container {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="navbar">
            <div class="logo">
                <img src="{{ asset('images/logo/kaya-boys-logo.png') }}" alt="Kaya Boys Logo" style="height: 50px; width: auto;">
            </div>
            <ul class="nav-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('menu') }}">Menu</a></li>
                <li><a href="#why">Why Us</a></li>
                <li><a href="#contact">Contact</a></li>

                @guest
                <li>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">Login</a>
                </li>
                @endguest

                @auth
                @if(Auth::user()->role === 'courier')
                <!-- Courier Navigation -->
                <li>
                    <a href="{{ route('courier.deliveries') }}" style="padding: 0.45rem 0.9rem; margin-right:0.5rem;">🚚 My Deliveries</a>
                </li>
                @else
                <!-- Customer Navigation -->
                <li>
                    <a href="{{ route('cart.index') }}" class="cart-btn" style="padding: 0.45rem 0.9rem; margin-right:0.5rem;">🛒 Cart</a>
                </li>
                <li>
                    <a href="{{ route('orders.index') }}" style="padding: 0.45rem 0.9rem; margin-right:0.5rem;">📦 My Orders</a>
                </li>
                @endif
                <li>
                    <a href="{{ route('profile.show') }}" style="font-weight:600;">{{ Auth::user()->name }}</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-secondary" style="padding: 0.45rem 0.9rem;">Logout</button>
                    </form>
                </li>
                @if(Auth::user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" style="padding: 0.45rem 0.9rem; font-size: 1.2rem;">👑</a>
                </li>
                @endif
                @if(Auth::user()->role === 'courier')
                <li>
                    <a href="{{ route('courier.dashboard') }}" style="padding: 0.45rem 0.9rem; font-size: 1.2rem;">🚚</a>
                </li>
                @endif
                @endauth
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <img src="{{ asset('images/logo/kaya-boys-logo.png') }}" alt="Kaya Boys Logo" style="height: 60px; width: auto; margin-bottom: 1rem;">
                <p>Delivering delicious sandwiches to your doorstep in Surabaya.</p>
                <div class="social-links">
                    <a href="#" title="Facebook">f</a>
                    <a href="#" title="Instagram">📷</a>
                    <a href="#" title="Twitter">𝕏</a>
                    <a href="#" title="WhatsApp">💬</a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Menu</a></li>
                    <li><a href="#">Order Tracking</a></li>
                    <li><a href="#">Account</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Operating Hours</h3>
                <ul>
                    <li>Monday - Friday: 10 AM - 10 PM</li>
                    <li>Saturday - Sunday: 9 AM - 11 PM</li>
                    <li>Delivery: 15-30 minutes</li>
                    <li>📍 Surabaya, Indonesia</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Kaya Boys. All rights reserved. | ALP Web Dev Project</p>
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
