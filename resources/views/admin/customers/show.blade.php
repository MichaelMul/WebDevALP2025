@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Customer Details</h2>
                <a href="{{ route('admin.customers.index') }}" 
                   style="background: #6b7280; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                    ← Back to Customers
                </a>
            </div>
        </div>

        <!-- Customer Info -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">Personal Information</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Name</label>
                    <p style="font-size: 1rem; font-weight: 600; color: #374151;">{{ $customer->user->name }}</p>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Email</label>
                    <p style="font-size: 1rem; color: #374151;">{{ $customer->user->email }}</p>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Phone</label>
                    <p style="font-size: 1rem; color: #374151;">{{ $customer->user->phone ?? '-' }}</p>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Tier</label>
                    <p style="font-size: 1rem; color: #374151;">
                        @if($customer->tier === 'bronze')
                            🥉 Bronze
                        @elseif($customer->tier === 'silver')
                            🥈 Silver
                        @else
                            🥇 Gold
                        @endif
                    </p>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Points</label>
                    <p style="font-size: 1.25rem; font-weight: 700; color: #f59e0b;">{{ number_format($customer->points) }} pts</p>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Wallet Balance</label>
                    <p style="font-size: 1.25rem; font-weight: 700; color: #10b981;">Rp {{ number_format($customer->balance, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">Order History</h3>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <tr>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Order #</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Date</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Status</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Payment</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 1rem 1.5rem; color: #374151; font-weight: 600;">#{{ $order->id }}</td>
                            <td style="padding: 1rem 1.5rem; color: #6b7280;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td style="padding: 1rem 1.5rem; text-align: center;">
                                @if($order->status === 'pending')
                                    <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Pending</span>
                                @elseif($order->status === 'processing')
                                    <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Processing</span>
                                @elseif($order->status === 'completed')
                                    <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Completed</span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: center; text-transform: capitalize; color: #6b7280;">
                                {{ $order->payment_method }}
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; font-weight: 600; color: #374151;">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 2rem; text-align: center; color: #9ca3af;">
                                No orders yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
