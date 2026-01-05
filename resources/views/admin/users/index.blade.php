@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Manage Users</h2>
                <a href="{{ route('admin.users.create') }}" 
                   style="background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    + Create User
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">ID</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Name</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Email</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Role</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Status</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Joined</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem 1.5rem; color: #374151;">{{ $user->id }}</td>
                        <td style="padding: 1rem 1.5rem; color: #374151; font-weight: 600;">{{ $user->name }}</td>
                        <td style="padding: 1rem 1.5rem; color: #6b7280;">{{ $user->email }}</td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            @if($user->role === 'admin')
                                <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    👑 Admin
                                </span>
                            @elseif($user->role === 'courier')
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    🚚 Courier
                                </span>
                            @else
                                <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    👤 Customer
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            @if($user->email_verified_at)
                                <span style="color: #10b981; font-size: 0.75rem; font-weight: 600;">✓ Verified</span>
                            @else
                                <span style="color: #ef4444; font-size: 0.75rem; font-weight: 600;">✗ Unverified</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center; color: #6b7280; font-size: 0.875rem;">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; margin-right: 0.5rem; font-size: 0.875rem; display: inline-block;">
                                View
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               style="background: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; margin-right: 0.5rem; font-size: 0.875rem; display: inline-block;">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; padding: 0.5rem 1rem; border-radius: 4px; border: none; cursor: pointer; font-size: 0.875rem;">
                                    Delete
                                </button>
                            </form>
                            @else
                            <span style="color: #9ca3af; font-size: 0.75rem; font-style: italic;">You</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #9ca3af;">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection
