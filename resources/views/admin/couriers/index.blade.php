@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Manage Couriers</h2>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Couriers Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">ID</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Name</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Email</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Phone</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Status</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Rating</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Deliveries</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($couriers as $courier)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem 1.5rem; color: #374151;">{{ $courier->id }}</td>
                        <td style="padding: 1rem 1.5rem; color: #374151; font-weight: 600;">{{ $courier->user->name }}</td>
                        <td style="padding: 1rem 1.5rem; color: #6b7280;">{{ $courier->user->email }}</td>
                        <td style="padding: 1rem 1.5rem; color: #6b7280;">{{ $courier->user->phone ?? '-' }}</td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            @if($courier->is_available)
                                <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    🟢 Available
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    🔴 Busy
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center; color: #374151;">
                            ⭐ {{ number_format($courier->rating, 1) }}
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center; color: #374151; font-weight: 600;">
                            {{ $courier->total_deliveries }}
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            <a href="{{ route('admin.couriers.edit', $courier) }}" 
                               style="background: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; margin-right: 0.5rem; font-size: 0.875rem; display: inline-block;">
                                Edit
                            </a>
                            <form action="{{ route('admin.couriers.destroy', $courier) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Delete this courier? This will also delete their user account.');">
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
                        <td colspan="8" style="padding: 2rem; text-align: center; color: #9ca3af;">
                            No couriers found. Drivers can register using the registration page.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
