<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierDashboardController extends Controller
{
    /**
     * Show courier dashboard with available deliveries
     */
    public function index()
    {
        $courier = Auth::user()->courier;
        
        if (!$courier) {
            return redirect()->route('dashboard')->with('error', 'Courier profile not found.');
        }

        // Get available orders (pending or confirmed) without assigned courier
        $availableOrders = Order::whereIn('order_status', ['pending', 'confirmed'])
            ->whereDoesntHave('delivery')
            ->with(['customer.user', 'orderItems.product'])
            ->latest()
            ->get();

        // Get courier's active deliveries
        $activeDeliveries = Delivery::where('courier_id', $courier->id)
            ->whereIn('status', ['picked_up', 'on_the_way'])
            ->with(['order.customer.user', 'order.orderItems.product'])
            ->latest()
            ->get();

        // Get completed deliveries
        $completedDeliveries = Delivery::where('courier_id', $courier->id)
            ->where('status', 'delivered')
            ->with(['order.customer.user'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_deliveries' => $courier->total_deliveries ?? 0,
            'rating' => $courier->rating ?? 0,
            'active_count' => $activeDeliveries->count(),
            'completed_today' => Delivery::where('courier_id', $courier->id)
                ->where('status', 'delivered')
                ->whereDate('delivery_time', today())
                ->count(),
        ];

        return view('courier.dashboard', compact('availableOrders', 'activeDeliveries', 'completedDeliveries', 'courier', 'stats'));
    }

    /**
     * Accept an order for delivery
     */
    public function acceptOrder(Order $order)
    {
        $courier = Auth::user()->courier;

        if (!$courier) {
            return back()->with('error', 'Courier profile not found.');
        }

        // Check if order already has a delivery
        if ($order->delivery) {
            return back()->with('error', 'This order has already been accepted by another courier.');
        }

        // Create delivery record
        Delivery::create([
            'order_id' => $order->id,
            'courier_id' => $courier->id,
            'status' => 'pending',
            'pickup_time' => now(),
        ]);

        // Update order status
        $order->update(['order_status' => 'picked_up']);

        return back()->with('success', 'Order accepted! Start delivering.');
    }

    /**
     * Update delivery status
     */
    public function updateStatus(Delivery $delivery, Request $request)
    {
        $request->validate([
            'status' => 'required|in:picked_up,in_transit,delivered',
        ]);

        $courier = Auth::user()->courier;

        if ($delivery->courier_id !== $courier->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $delivery->update(['status' => $request->status]);

        if ($request->status === 'picked_up') {
            $delivery->order->update(['order_status' => 'picked_up']);
        } elseif ($request->status === 'in_transit') {
            $delivery->order->update(['order_status' => 'in_transit']);
        } elseif ($request->status === 'delivered') {
            $delivery->update(['delivery_time' => now()]);
            $delivery->order->update(['order_status' => 'delivered']);
            
            // Increment courier's total deliveries
            $courier->increment('total_deliveries');
        }

        return back()->with('success', 'Delivery status updated!');
    }

    /**
     * Toggle courier availability
     */
    public function toggleAvailability()
    {
        $courier = Auth::user()->courier;
        $courier->update(['is_available' => !$courier->is_available]);

        $status = $courier->is_available ? 'online' : 'offline';
        return back()->with('success', "You are now {$status}!");
    }

    /**
     * Show all deliveries for the courier (like "My Orders" for customers)
     */
    public function myDeliveries()
    {
        $courier = Auth::user()->courier;

        if (!$courier) {
            return redirect()->route('dashboard')->with('error', 'Courier profile not found.');
        }

        // Get all deliveries assigned to this courier
        $deliveries = Delivery::where('courier_id', $courier->id)
            ->with(['order.customer.user', 'order.orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('courier.deliveries', compact('deliveries', 'courier'));
    }
}
