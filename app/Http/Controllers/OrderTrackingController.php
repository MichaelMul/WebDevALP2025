<?php

namespace App\Http\Controllers;

use App\Models\OrderTracking;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OrderTracking::with('order');

        // Filter by order
        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trackings = $query->latest()->paginate(20);
        $orders = Order::latest()->get();

        return view('admin.order-tracking.index', compact('trackings', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::latest()->get();
        return view('admin.order-tracking.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        OrderTracking::create($request->all());

        return redirect()->route('admin.order-tracking.index')
            ->with('success', 'Order tracking entry created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderTracking $orderTracking)
    {
        $orderTracking->load('order');
        return view('admin.order-tracking.show', compact('orderTracking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderTracking $orderTracking)
    {
        $orders = Order::latest()->get();
        return view('admin.order-tracking.edit', compact('orderTracking', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderTracking $orderTracking)
    {
        $request->validate([
            'status' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $orderTracking->update($request->all());

        return redirect()->route('admin.order-tracking.index')
            ->with('success', 'Order tracking entry updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderTracking $orderTracking)
    {
        $orderTracking->delete();

        return redirect()->route('admin.order-tracking.index')
            ->with('success', 'Order tracking entry deleted successfully');
    }
}
