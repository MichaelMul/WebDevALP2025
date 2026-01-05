<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Courier;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries.
     */
    public function index()
    {
        $deliveries = Delivery::with(['order.customer.user', 'courier.user'])
            ->latest()
            ->paginate(20);
        return view('admin.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new delivery.
     */
    public function create()
    {
        $orders = Order::whereDoesntHave('delivery')
            ->whereIn('order_status', ['pending', 'confirmed'])
            ->with('customer.user')
            ->get();
        $couriers = Courier::with('user')->where('is_available', true)->get();
        return view('admin.deliveries.create', compact('orders', 'couriers'));
    }

    /**
     * Store a newly created delivery.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'courier_id' => 'required|exists:couriers,id',
            'status' => 'required|in:pending,picked_up,in_transit,delivered,cancelled',
        ]);

        $delivery = Delivery::create($validated);

        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery created successfully');
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['order.customer.user', 'order.orderItems.product', 'courier.user']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the delivery.
     */
    public function edit(Delivery $delivery)
    {
        $delivery->load(['order.customer.user', 'courier.user']);
        $couriers = Courier::with('user')->get();
        return view('admin.deliveries.edit', compact('delivery', 'couriers'));
    }

    /**
     * Update the specified delivery.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'status' => 'required|in:pending,picked_up,in_transit,delivered,cancelled',
        ]);

        $delivery->update($validated);

        // Update order status to match delivery status
        $delivery->order->update(['order_status' => $validated['status']]);

        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery updated successfully');
    }

    /**
     * Remove the specified delivery.
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery deleted successfully');
    }
}
