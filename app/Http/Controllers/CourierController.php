<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index() {
        $couriers = Courier::with('user')->get();
        return view('admin.couriers.index', compact('couriers'));
    }

    public function create() {
        $users = User::all();
        return view('admin.couriers.create', compact('users'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'current_latitude' => 'nullable|numeric',
            'current_longitude' => 'nullable|numeric',
            'ratings' => 'nullable|numeric',
            'total_deliveries' => 'nullable|integer',
        ]);
        $validated['is_available'] = $request->has('is_available');

        Courier::create($validated);
        return redirect()->route('admin.couriers.index');
    }

    public function edit(Courier $courier) {
        $courier->load('user');
        return view('admin.couriers.edit', compact('courier'));
    }

    public function update(Request $request, Courier $courier) {
        $validated = $request->validate([
            'is_available' => 'nullable|boolean',
            'role' => 'required|in:customer,courier',
        ]);

        // Handle role switching from courier to customer
        if ($validated['role'] === 'customer') {
            \DB::transaction(function() use ($courier) {
                $user = $courier->user;
                $user->update(['role' => 'customer']);
                
                // Create customer record
                \App\Models\Customer::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'points' => 0,
                    'tier' => 'bronze',
                ]);
                
                // Delete courier record
                $courier->delete();
            });
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Courier converted to customer successfully.');
        }

        // Update courier data
        $courier->update([
            'is_available' => $request->has('is_available'),
        ]);
        
        return redirect()->route('admin.couriers.index')
            ->with('success', 'Courier updated successfully.');
    }

    public function destroy(Courier $courier) {
        $courier->delete();
        return redirect()->route('admin.couriers.index');
    }
}
