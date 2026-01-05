@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Manage Deliveries</h2>
                <a href="{{ route('admin.deliveries.create') }}" 
                   style="background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    + Create Delivery
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Deliveries Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">ID</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Order #</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Courier</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Status</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Rating</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Created</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem 1.5rem; color: #374151;">{{ $delivery->id }}</td>
                        <td style="padding: 1rem 1.5rem; color: #374151; font-weight: 600;">{{ $delivery->order->order_number }}</td>
                        <td style="padding: 1rem 1.5rem; color: #6b7280;">{{ $delivery->courier->user->name }}</td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            @if($delivery->status === 'delivered')
                                <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    ✅ Delivered
                                </span>
                            @elseif($delivery->status === 'in_transit')
                                <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    🚚 In Transit
                                </span>
                            @elseif($delivery->status === 'picked_up')
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    📦 Picked Up
                                </span>
                            @elseif($delivery->status === 'cancelled')
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    ❌ Cancelled
                                </span>
                            @else
                                <span style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    ⏳ Pending
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            @if($delivery->customer_rating)
                                <span style="color: #f59e0b; font-weight: 600;">{{ $delivery->customer_rating }} ⭐</span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center; color: #6b7280; font-size: 0.875rem;">
                            {{ $delivery->created_at->format('M d, Y') }}
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                               style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; margin-right: 0.5rem; font-size: 0.875rem; display: inline-block;">
                                View
                            </a>
                            <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                               style="background: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; margin-right: 0.5rem; font-size: 0.875rem; display: inline-block;">
                                Edit
                            </a>
                            <form action="{{ route('admin.deliveries.destroy', $delivery) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Delete this delivery?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; padding: 0.5rem 1rem; border-radius: 4px; border: none; cursor: pointer; font-size: 0.875rem;">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #9ca3af;">
                            No deliveries found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            {{ $deliveries->links() }}
        </div>

    </div>
</div>
@endsection
