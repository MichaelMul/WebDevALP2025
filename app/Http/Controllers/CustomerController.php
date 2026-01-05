<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::with('user')->get();
        return view('customers.index', compact('customers'));
    }

    public function create() {
        $users = User::all();
        return view('customers.create', compact('users'));
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
        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer) {
        $users = User::all();
        return view('customers.edit', compact('customer', 'users'));
    }

    public function update(Request $request, Customer $customer) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city' => 'nullable|string',
            'wallet_balance' => 'nullable|numeric',
            'points' => 'nullable|integer',
            'membership_tier' => 'nullable|string|in:bronze,silver,gold',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer) {
        $customer->delete();
        return redirect()->route('customers.index');
    }
}
