<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderItems = OrderItem::with(['order', 'product'])
            ->latest()
            ->paginate(20);

        return view('admin.order-items.index', compact('orderItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::latest()->get();
        $products = Product::where('stock', '>', 0)->get();

        return view('admin.order-items.create', compact('orders', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->quantity * $request->price;

        OrderItem::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'subtotal' => $subtotal,
        ]);

        // Update order total
        $order = Order::find($request->order_id);
        $order->total_amount = $order->items->sum('subtotal');
        $order->save();

        return redirect()->route('admin.order-items.index')
            ->with('success', 'Order item added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        $orderItem->load(['order', 'product']);
        return view('admin.order-items.show', compact('orderItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        $orders = Order::latest()->get();
        $products = Product::all();

        return view('admin.order-items.edit', compact('orderItem', 'orders', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->quantity * $request->price;

        $orderItem->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'subtotal' => $subtotal,
        ]);

        // Update order total
        $order = $orderItem->order;
        $order->total_amount = $order->items->sum('subtotal');
        $order->save();

        return redirect()->route('admin.order-items.index')
            ->with('success', 'Order item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        $order = $orderItem->order;
        $orderItem->delete();

        // Update order total
        $order->total_amount = $order->items->sum('subtotal');
        $order->save();

        return redirect()->route('admin.order-items.index')
            ->with('success', 'Order item deleted successfully');
    }
}
