@extends('layouts.app')

@section('title', 'My Profile - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 900px; margin: 0 auto;">
        <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: #1a1a1a;">My Profile</h1>

        <div style="display: grid; gap: 2rem;">
            <!-- User Info Card -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 700;">Account Information</h2>
                    <a href="{{ route('profile.edit') }}" style="background: #D97706; color: white; padding: 0.6rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">Edit Profile</a>
                </div>

                <div style="display: grid; gap: 1rem;">
                    <div>
                        <div style="color: #666; font-size: 0.9rem; margin-bottom: 0.3rem;">Name</div>
                        <div style="font-weight: 600; font-size: 1.1rem;">{{ $user->name }}</div>
                    </div>

                    <div>
                        <div style="color: #666; font-size: 0.9rem; margin-bottom: 0.3rem;">Email</div>
                        <div style="font-weight: 600; font-size: 1.1rem;">{{ $user->email }}</div>
                    </div>

                    <div>
                        <div style="color: #666; font-size: 0.9rem; margin-bottom: 0.3rem;">Phone</div>
                        <div style="font-weight: 600; font-size: 1.1rem;">{{ $user->phone ?? 'Not provided' }}</div>
                    </div>

                    <div>
                        <div style="color: #666; font-size: 0.9rem; margin-bottom: 0.3rem;">Member Since</div>
                        <div style="font-weight: 600; font-size: 1.1rem;">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Wallet & Points Card -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Wallet & Rewards</h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <!-- Wallet Balance -->
                    <div style="background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); border-radius: 12px; padding: 1.5rem; color: white;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="font-size: 2rem;">💰</div>
                            <div style="opacity: 0.9; font-size: 0.95rem;">Wallet Balance</div>
                        </div>
                        <div style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Rp {{ number_format($customer->wallet_balance, 0, ',', '.') }}</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                            <button onclick="document.getElementById('topupModal').style.display='flex'" style="width: 100%; background: white; color: #D97706; border: none; padding: 0.7rem; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9rem;">Top Up</button>
                            <a href="{{ route('wallet.history') }}" style="width: 100%; background: rgba(255,255,255,0.2); color: white; border: none; padding: 0.7rem; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9rem; text-decoration: none; text-align: center;">History</a>
                        </div>
                    </div>

                    <!-- Loyalty Points -->
                    <div style="background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border-radius: 12px; padding: 1.5rem; color: white;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="font-size: 2rem;">⭐</div>
                            <div style="opacity: 0.9; font-size: 0.95rem;">Loyalty Points</div>
                        </div>
                        <div style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">{{ number_format($customer->points) }} pts</div>
                        <div style="opacity: 0.9; font-size: 0.9rem;">Tier: <span style="font-weight: 700; text-transform: capitalize;">{{ $customer->membership_tier }}</span></div>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; padding: 1rem; background: #FFFBF0; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #666;">
                        💡 <strong>Earn points</strong> with every order! Points can be redeemed for discounts on future purchases.
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Quick Actions</h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <a href="{{ route('orders.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 2px solid #F0F0F0; border-radius: 8px; text-decoration: none; color: #1a1a1a; transition: all 0.3s;">
                        <div style="font-size: 2rem;">📦</div>
                        <div>
                            <div style="font-weight: 700;">My Orders</div>
                            <div style="color: #666; font-size: 0.9rem;">View order history</div>
                        </div>
                    </a>

                    <a href="{{ route('cart.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 2px solid #F0F0F0; border-radius: 8px; text-decoration: none; color: #1a1a1a; transition: all 0.3s;">
                        <div style="font-size: 2rem;">🛒</div>
                        <div>
                            <div style="font-weight: 700;">Shopping Cart</div>
                            <div style="color: #666; font-size: 0.9rem;">View your cart</div>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 2px solid #F0F0F0; border-radius: 8px; text-decoration: none; color: #1a1a1a; transition: all 0.3s;">
                        <div style="font-size: 2rem;">🥪</div>
                        <div>
                            <div style="font-weight: 700;">Browse Menu</div>
                            <div style="color: #666; font-size: 0.9rem;">Order sandwiches</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Up Modal -->
<div id="topupModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 500px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.5rem; font-weight: 700;">Top Up Wallet</h3>
            <button onclick="document.getElementById('topupModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>

        <form action="{{ route('wallet.topup') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Amount</label>
                <input type="number" name="amount" min="10000" step="1000" placeholder="Enter amount (min. Rp 10.000)" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Payment Method</label>
                <select name="payment_method" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    <option value="">Select payment method</option>
                    <option value="qris">QRIS</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <button type="button" onclick="document.getElementById('topupModal').style.display='none'" style="background: #E5E7EB; color: #1a1a1a; border: none; padding: 0.8rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" style="background: #D97706; color: white; border: none; padding: 0.8rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Proceed</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div style="position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem 2rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    {{ session('success') }}
</div>
@endif

<style>
    a:hover {
        border-color: #D97706 !important;
        transform: translateY(-2px);
    }
</style>
@endsection
