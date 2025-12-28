@extends('layouts.app')

@section('title', 'Register - Kaya Boys')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #FFFBF0; padding: 2rem;">
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 3rem; max-width: 420px; width: 100%;">
        
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="{{ asset('images/logo/kaya-boys-logo.png') }}" alt="Kaya Boys Logo" style="height: 60px; width: auto; margin-bottom: 1rem;">
            <h1 style="font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.5rem;">Create Account</h1>
            <p style="color: #666; font-size: 0.95rem;">Join Kaya Boys and start ordering delicious sandwiches</p>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" style="margin-bottom: 1.5rem;">
            @csrf

            <!-- Full Name -->
            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #DDD; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; box-sizing: border-box;">
                @error('name')
                    <span style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #DDD; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; box-sizing: border-box;">
                @error('email')
                    <span style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem;">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #DDD; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; box-sizing: border-box;">
                @error('password')
                    <span style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div style="margin-bottom: 1.5rem;">
                <label for="password_confirmation" style="display: block; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem;">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #DDD; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; box-sizing: border-box;">
                @error('password_confirmation')
                    <span style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Register Button -->
            <button type="submit" style="width: 100%; background: #D97706; color: white; padding: 0.9rem; border: none; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: background 0.3s; margin-bottom: 2rem;">
                Create Account
            </button>
        </form>

        <!-- Sign In Section -->
        <div style="text-align: center;">
            <p style="color: #666; font-size: 0.95rem; margin-bottom: 1rem;">Already have an account?</p>
            <a href="{{ route('login') }}" style="display: inline-block; background: transparent; color: #D97706; padding: 0.9rem 2rem; border: 2px solid #D97706; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; text-decoration: none; transition: all 0.3s; box-sizing: border-box;">
                Sign In Instead
            </a>
        </div>

        <!-- Back to Home -->
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ url('/') }}" style="color: #666; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">
                ← Back to home
            </a>
        </div>
    </div>
</div>
@endsection
