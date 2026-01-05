<?php

namespace App\Http\Controllers;

use App\Models\PointsHistory;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class PointsHistoryController extends Controller
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
        $query = PointsHistory::with(['user', 'order']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $pointsHistory = $query->latest()->paginate(20);
        $users = User::all();

        return view('admin.points-history.index', compact('pointsHistory', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $orders = Order::latest()->get();

        return view('admin.points-history.create', compact('users', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'points' => 'required|integer',
            'type' => 'required|in:earned,redeemed,expired',
            'description' => 'nullable|string',
        ]);

        PointsHistory::create($request->all());

        return redirect()->route('admin.points-history.index')
            ->with('success', 'Points history entry created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(PointsHistory $pointsHistory)
    {
        $pointsHistory->load(['user', 'order']);
        return view('admin.points-history.show', compact('pointsHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PointsHistory $pointsHistory)
    {
        $users = User::all();
        $orders = Order::latest()->get();

        return view('admin.points-history.edit', compact('pointsHistory', 'users', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PointsHistory $pointsHistory)
    {
        $request->validate([
            'points' => 'required|integer',
            'type' => 'required|in:earned,redeemed,expired',
            'description' => 'nullable|string',
        ]);

        $pointsHistory->update($request->only(['points', 'type', 'description']));

        return redirect()->route('admin.points-history.index')
            ->with('success', 'Points history entry updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointsHistory $pointsHistory)
    {
        $pointsHistory->delete();

        return redirect()->route('admin.points-history.index')
            ->with('success', 'Points history entry deleted successfully');
    }
}
