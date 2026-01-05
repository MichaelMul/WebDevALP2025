<?php

namespace App\Http\Controllers;

use App\Models\Cancellation;
use App\Models\Order;
use Illuminate\Http\Request;

class CancellationController extends Controller
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
        $query = Cancellation::with('order');

        // Filter by cancelled_by
        if ($request->filled('cancelled_by')) {
            $query->where('cancelled_by', $request->cancelled_by);
        }

        // Filter by refund_status
        if ($request->filled('refund_status')) {
            $query->where('refund_status', $request->refund_status);
        }

        $cancellations = $query->latest()->paginate(20);

        return view('admin.cancellations.index', compact('cancellations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::latest()->get();
        return view('admin.cancellations.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id|unique:cancellations,order_id',
            'cancelled_by' => 'required|in:customer,admin,courier',
            'reason' => 'required|string',
            'refund_amount' => 'required|numeric|min:0',
            'refund_status' => 'required|in:pending,processed,failed',
        ]);

        Cancellation::create($request->all());

        // Update order status
        $order = Order::find($request->order_id);
        $order->order_status = 'cancelled';
        $order->save();

        return redirect()->route('admin.cancellations.index')
            ->with('success', 'Cancellation record created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cancellation $cancellation)
    {
        $cancellation->load('order');
        return view('admin.cancellations.show', compact('cancellation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cancellation $cancellation)
    {
        return view('admin.cancellations.edit', compact('cancellation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cancellation $cancellation)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0',
            'refund_status' => 'required|in:pending,processed,failed',
            'reason' => 'required|string',
        ]);

        $cancellation->update($request->only(['refund_amount', 'refund_status', 'reason']));

        return redirect()->route('admin.cancellations.index')
            ->with('success', 'Cancellation record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cancellation $cancellation)
    {
        $cancellation->delete();

        return redirect()->route('admin.cancellations.index')
            ->with('success', 'Cancellation record deleted successfully');
    }
}
