@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Edit Customer</h2>
                <a href="{{ route('admin.customers.index') }}" 
                   style="background: #6b7280; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                    ← Back
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Edit Form -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
            <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- User Info (Read-only) -->
                <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; color: #374151;">User Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Name</label>
                            <div style="font-weight: 600; color: #1f2937;">{{ $customer->user->name }}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Email</label>
                            <div style="font-weight: 600; color: #1f2937;">{{ $customer->user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Address</label>
                    <input type="text" name="address" value="{{ old('address', $customer->address) }}"
                           style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    @error('address')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                    <!-- Postal Code -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}"
                               style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    </div>

                    <!-- City -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">City</label>
                        <input type="text" name="city" value="{{ old('city', $customer->city) }}"
                               style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                    <!-- Balance -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Wallet Balance (Rp)</label>
                        <input type="number" name="balance" value="{{ old('balance', $customer->balance) }}" min="0" step="1000"
                               style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    </div>

                    <!-- Points -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Loyalty Points</label>
                        <input type="number" name="points" value="{{ old('points', $customer->points) }}" min="0"
                               style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    </div>
                </div>

                <!-- Tier -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Membership Tier</label>
                    <select name="tier" style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                        <option value="bronze" {{ $customer->tier === 'bronze' ? 'selected' : '' }}>🥉 Bronze</option>
                        <option value="silver" {{ $customer->tier === 'silver' ? 'selected' : '' }}>🥈 Silver</option>
                        <option value="gold" {{ $customer->tier === 'gold' ? 'selected' : '' }}>🥇 Gold</option>
                    </select>
                </div>

                <!-- Role Switcher -->
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: #fef3c7; border-radius: 6px; border: 1px solid #fbbf24;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #92400e;">⚠️ Account Role</label>
                    <select name="role" style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                        <option value="customer">Customer (Current)</option>
                        <option value="courier">🚚 Convert to Courier</option>
                    </select>
                    <p style="font-size: 0.875rem; color: #92400e; margin-top: 0.5rem;">
                        ⚠️ Converting to courier will delete customer data and create a courier account.
                    </p>
                </div>

                <!-- Submit -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <a href="{{ route('admin.customers.index') }}" 
                       style="background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                        Cancel
                    </a>
                    <button type="submit" 
                            style="background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
