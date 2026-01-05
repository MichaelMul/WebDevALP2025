@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">User Details</h2>
                <a href="{{ route('admin.users.index') }}" 
                   style="background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    Back to Users
                </a>
            </div>
        </div>

        <!-- User Info -->
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">User Information</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Name</div>
                    <div style="font-weight: 600; color: #1f2937;">{{ $user->name }}</div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Email</div>
                    <div style="font-weight: 600; color: #1f2937;">{{ $user->email }}</div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Role</div>
                    <div style="font-weight: 600; color: #1f2937;">
                        @if($user->role === 'admin')
                            <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                👑 Admin
                            </span>
                        @elseif($user->role === 'courier')
                            <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                🚚 Courier
                            </span>
                        @else
                            <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                👤 Customer
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Status</div>
                    <div style="font-weight: 600;">
                        @if($user->email_verified_at)
                            <span style="color: #10b981;">✓ Verified</span>
                        @else
                            <span style="color: #ef4444;">✗ Not Verified</span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Joined</div>
                    <div style="font-weight: 600; color: #1f2937;">{{ $user->created_at->format('M d, Y - H:i') }}</div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Last Updated</div>
                    <div style="font-weight: 600; color: #1f2937;">{{ $user->updated_at->format('M d, Y - H:i') }}</div>
                </div>
            </div>
        </div>

        @if($user->customer)
        <!-- Customer Profile -->
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">Customer Profile</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Wallet Balance</div>
                    <div style="font-weight: 600; color: #10b981;">Rp {{ number_format($user->customer->wallet_balance, 0, ',', '.') }}</div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Delivery Address</div>
                    <div style="font-weight: 600; color: #1f2937;">{{ $user->customer->delivery_address ?? 'Not set' }}</div>
                </div>
            </div>
        </div>
        @endif

        @if($user->courier)
        <!-- Courier Profile -->
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">Courier Profile</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Rating</div>
                    <div style="font-weight: 600; color: #f59e0b;">⭐ {{ number_format($user->courier->rating, 2) }}</div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Total Deliveries</div>
                    <div style="font-weight: 600; color: #1f2937;">{{ $user->courier->total_deliveries }}</div>
                </div>
                
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Status</div>
                    <div style="font-weight: 600;">
                        @if($user->courier->is_available)
                            <span style="color: #10b981;">🟢 Available</span>
                        @else
                            <span style="color: #ef4444;">🔴 Offline</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   style="flex: 1; background: #f59e0b; color: white; padding: 1rem; border-radius: 4px; text-align: center; text-decoration: none; font-weight: 600;">
                    Edit User
                </a>
                
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="flex: 1;" 
                      onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width: 100%; background: #ef4444; color: white; padding: 1rem; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        Delete User
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
