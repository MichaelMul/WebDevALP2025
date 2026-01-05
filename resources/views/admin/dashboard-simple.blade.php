@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div style="background: #F3F4F6; min-height: 100vh; padding: 2rem;">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <div style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); border-radius: 16px; padding: 2rem; margin-bottom: 2rem; color: white;">
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">👋 Welcome, {{ $adminName }}!</h1>
            <p style="opacity: 0.9;">Admin Control Panel - Manage everything from here</p>
        </div>

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">📦 Total Products</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #3B82F6;">{{ $totalProducts }}</div>
                <a href="{{ route('admin.products.index') }}" style="color: #3B82F6; font-size: 0.875rem; text-decoration: none;">Manage Products →</a>
            </div>

            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">📂 Total Categories</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #8B5CF6;">{{ $totalCategories }}</div>
                <a href="{{ route('admin.categories.index') }}" style="color: #8B5CF6; font-size: 0.875rem; text-decoration: none;">Manage Categories →</a>
            </div>

            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">🚚 Total Couriers</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #10B981;">{{ $totalCouriers }}</div>
                <a href="{{ route('admin.couriers.index') }}" style="color: #10B981; font-size: 0.875rem; text-decoration: none;">Manage Couriers →</a>
            </div>

            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">👥 Total Customers</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #F59E0B;">{{ $totalCustomers }}</div>
                <a href="{{ route('admin.customers.index') }}" style="color: #F59E0B; font-size: 0.875rem; text-decoration: none;">Manage Customers →</a>
            </div>
        </div>

        <!-- Quick Links -->
        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1F2937;">⚡ Quick Actions</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('admin.products.index') }}" style="padding: 1rem; background: #F3F4F6; border-radius: 8px; text-decoration: none; color: #1F2937; transition: all 0.2s;">
                    <div style="font-weight: 600;">Manage Products</div>
                    <div style="font-size: 0.875rem; color: #6B7280;">Add, edit, or delete products</div>
                </a>
                <a href="{{ route('admin.categories.index') }}" style="padding: 1rem; background: #F3F4F6; border-radius: 8px; text-decoration: none; color: #1F2937; transition: all 0.2s;">
                    <div style="font-weight: 600;">Manage Categories</div>
                    <div style="font-size: 0.875rem; color: #6B7280;">Organize product categories</div>
                </a>
                <a href="{{ route('admin.couriers.index') }}" style="padding: 1rem; background: #F3F4F6; border-radius: 8px; text-decoration: none; color: #1F2937; transition: all 0.2s;">
                    <div style="font-weight: 600;">Manage Couriers</div>
                    <div style="font-size: 0.875rem; color: #6B7280;">View and manage drivers</div>
                </a>
                <a href="{{ route('admin.customers.index') }}" style="padding: 1rem; background: #F3F4F6; border-radius: 8px; text-decoration: none; color: #1F2937; transition: all 0.2s;">
                    <div style="font-weight: 600;">Manage Customers</div>
                    <div style="font-size: 0.875rem; color: #6B7280;">View customer accounts</div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
