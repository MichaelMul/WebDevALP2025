@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Edit User</h2>
        </div>

        <!-- Form -->
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">
                    @error('name')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">
                    @error('email')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Role</label>
                    <select name="role" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="courier" {{ old('role', $user->role) === 'courier' ? 'selected' : '' }}>Courier</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password (Optional) -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Password (leave blank to keep current)</label>
                    <input type="password" name="password"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">
                    @error('password')
                        <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;">
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" 
                            style="flex: 1; background: #f59e0b; color: white; padding: 0.75rem; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" 
                       style="flex: 1; background: #6b7280; color: white; padding: 0.75rem; border-radius: 4px; font-weight: 600; text-align: center; text-decoration: none; display: block;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
