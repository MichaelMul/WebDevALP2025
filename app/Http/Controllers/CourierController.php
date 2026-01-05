<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index() {
        $couriers = Courier::with('user')->get();
        return view('couriers.index', compact('couriers'));
    }

    public function create() {
        $users = User::all();
        return view('couriers.create', compact('users'));
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
        return redirect()->route('couriers.index');
    }

    public function edit(Courier $courier) {
        $users = User::all();
        return view('couriers.edit', compact('courier', 'users'));
    }

    public function update(Request $request, Courier $courier) {
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

        $courier->update($validated);
        return redirect()->route('couriers.index');
    }

    public function destroy(Courier $courier) {
        $courier->delete();
        return redirect()->route('couriers.index');
    }
}
