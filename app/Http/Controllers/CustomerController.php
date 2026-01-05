<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {
        // Only show users that are actually customers (exclude admin/courier accounts)
        $customers = Customer::with('user')
            ->whereHas('user', fn($q) => $q->where('role', 'customer'))
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function create() {
        // Limit selectable users to customer role only
        $users = User::where('role', 'customer')->get();
        return view('admin.customers.create', compact('users'));
    }

    public function show(Customer $customer) {
        $customer->load(['user', 'orders']);
        return view('admin.customers.show', compact('customer'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city' => 'nullable|string',
            'wallet_balance' => 'nullable|numeric',
            'points' => 'nullable|integer',
            'membership_tier' => 'nullable|string|in:bronze,silver,gold',
        ]);
        Customer::create($validated);
        return redirect()->route('admin.customers.index');
    }

    public function edit(Customer $customer) {
        $customer->load('user');
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer) {
        $validated = $request->validate([
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city' => 'nullable|string',
            'balance' => 'nullable|numeric',
            'points' => 'nullable|integer',
            'tier' => 'nullable|string|in:bronze,silver,gold',
            'role' => 'required|in:customer,courier',
        ]);

        // Handle role switching from customer to courier
        if ($validated['role'] === 'courier') {
            \DB::transaction(function() use ($customer) {
                $user = $customer->user;
                $user->update(['role' => 'courier']);
                
                // Create courier record
                \App\Models\Courier::create([
                    'user_id' => $user->id,
                    'is_available' => true,
                    'rating' => 5.0,
                    'total_deliveries' => 0,
                ]);
                
                // Delete customer record
                $customer->delete();
            });
            
            return redirect()->route('admin.couriers.index')
                ->with('success', 'Customer converted to courier successfully.');
        }

        // Update customer data
        $customer->update([
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'city' => $validated['city'],
            'balance' => $validated['balance'],
            'points' => $validated['points'],
            'tier' => $validated['tier'],
        ]);
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer) {
        $customer->delete();
        return redirect()->route('admin.customers.index');
    }
}
