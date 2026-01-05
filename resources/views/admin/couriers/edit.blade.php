@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Edit Courier</h2>
                <a href="{{ route('admin.couriers.index') }}" 
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
            <form action="{{ route('admin.couriers.update', $courier) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- User Info (Read-only) -->
                <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; color: #374151;">User Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Name</label>
                            <div style="font-weight: 600; color: #1f2937;">{{ $courier->user->name }}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Email</label>
                            <div style="font-weight: 600; color: #1f2937;">{{ $courier->user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Stats (Read-only) -->
                <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; color: #374151;">Courier Statistics</h3>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Rating</label>
                            <div style="font-weight: 600; color: #1f2937;">⭐ {{ number_format($courier->rating, 1) }}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Total Deliveries</label>
                            <div style="font-weight: 600; color: #1f2937;">{{ $courier->total_deliveries }}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Current Status</label>
                            <div style="font-weight: 600; color: {{ $courier->is_available ? '#065f46' : '#991b1b' }};">
                                {{ $courier->is_available ? '🟢 Available' : '🔴 Busy' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Availability Toggle -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="is_available" value="1" {{ $courier->is_available ? 'checked' : '' }}
                               style="width: 20px; height: 20px; margin-right: 0.75rem;">
                        <span style="font-weight: 600; color: #374151;">Courier is available for deliveries</span>
                    </label>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; margin-left: 1.75rem;">
                        Toggle courier availability status for accepting new delivery orders.
                    </p>
                </div>

                <!-- Role Switcher -->
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: #fef3c7; border-radius: 6px; border: 1px solid #fbbf24;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #92400e;">⚠️ Account Role</label>
                    <select name="role" style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                        <option value="courier">Courier (Current)</option>
                        <option value="customer">🛒 Convert to Customer</option>
                    </select>
                    <p style="font-size: 0.875rem; color: #92400e; margin-top: 0.5rem;">
                        ⚠️ Converting to customer will delete courier data (deliveries, rating) and create a customer account.
                    </p>
                </div>

                <!-- Submit -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <a href="{{ route('admin.couriers.index') }}" 
                       style="background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                        Cancel
                    </a>
                    <button type="submit" 
                            style="background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                        Update Courier
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
