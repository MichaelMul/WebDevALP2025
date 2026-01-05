<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class WalletController extends Controller
{
    /**
     * Process wallet top-up
     */
    public function topup(Request $request): RedirectResponse
    {
        // Validate input
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|in:qris,bank_transfer,ewallet',
        ], [
            'amount.min' => 'Minimum top-up amount is Rp 10.000',
            'amount.required' => 'Please enter an amount',
            'payment_method.required' => 'Please select a payment method',
        ]);

        // Get authenticated user and customer
        $user = auth()->user();
        $customer = $user->customer;

        if (!$customer) {
            $customer = Customer::create([
                'user_id' => $user->id,
            ]);
        }

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'topup',
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'success', // In real app, integrate with payment gateway
            'reference_number' => 'TXN-' . uniqid(),
        ]);

        // Update wallet balance
        $balanceBefore = $customer->wallet_balance;
        $customer->increment('wallet_balance', $validated['amount']);

        // Record wallet transaction
        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => $validated['amount'],
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $validated['amount'],
            'description' => 'Wallet top-up via ' . ucfirst(str_replace('_', ' ', $validated['payment_method'])),
            'reference_id' => $transaction->reference_number,
        ]);

        return redirect()->route('profile.show')->with('success', 'Wallet topped up successfully! Added Rp ' . number_format($validated['amount'], 0, ',', '.'));
    }

    /**
     * Show wallet transaction history
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('wallet.history', compact('transactions'));
    }

    // ===== ADMIN CRUD =====

    /**
     * Display all wallet transactions (admin)
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $transactions = WalletTransaction::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.wallet_transactions.index', compact('transactions'));
    }

    /**
     * Show form to create manual adjustment (admin)
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = \App\Models\User::whereHas('customer')->with('customer')->get();
        return view('admin.wallet_transactions.create', compact('users'));
    }

    /**
     * Store manual wallet adjustment (admin)
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:500',
        ]);

        $user = \App\Models\User::findOrFail($validated['user_id']);
        $customer = $user->customer;

        if (!$customer) {
            return redirect()->back()->with('error', 'User does not have a customer profile');
        }

        \DB::transaction(function() use ($customer, $validated) {
            $balanceBefore = $customer->wallet_balance;

            if ($validated['type'] === 'credit') {
                $customer->increment('wallet_balance', $validated['amount']);
            } else {
                $customer->decrement('wallet_balance', $validated['amount']);
            }

            $customer->refresh();

            WalletTransaction::create([
                'user_id' => $validated['user_id'],
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'balance_before' => $balanceBefore,
                'balance_after' => $customer->wallet_balance,
                'description' => $validated['description'],
                'reference_id' => 'ADMIN-' . uniqid(),
            ]);
        });

        return redirect()->route('admin.wallet-transactions.index')->with('success', 'Wallet adjustment created successfully');
    }

    /**
     * Display single transaction (admin)
     */
    public function show(WalletTransaction $walletTransaction)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $walletTransaction->load('user.customer');
        return view('admin.wallet_transactions.show', compact('walletTransaction'));
    }

    /**
     * Show form to edit transaction (admin)
     */
    public function edit(WalletTransaction $walletTransaction)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $walletTransaction->load('user');
        return view('admin.wallet_transactions.edit', compact('walletTransaction'));
    }

    /**
     * Update transaction (admin)
     */
    public function adminUpdate(Request $request, WalletTransaction $walletTransaction)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:500',
        ]);

        $walletTransaction->update($validated);

        return redirect()->route('admin.wallet-transactions.index')->with('success', 'Transaction updated successfully');
    }

    /**
     * Delete transaction (admin - void it)
     */
    public function destroy(WalletTransaction $walletTransaction)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Create reverse transaction
        $user = $walletTransaction->user;
        $customer = $user->customer;

        if ($customer) {
            \DB::transaction(function() use ($customer, $walletTransaction) {
                $balanceBefore = $customer->wallet_balance;
                $reverseType = $walletTransaction->type === 'credit' ? 'debit' : 'credit';
                
                if ($reverseType === 'credit') {
                    $customer->increment('wallet_balance', $walletTransaction->amount);
                } else {
                    $customer->decrement('wallet_balance', $walletTransaction->amount);
                }

                $customer->refresh();

                WalletTransaction::create([
                    'user_id' => $walletTransaction->user_id,
                    'type' => $reverseType,
                    'amount' => $walletTransaction->amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $customer->wallet_balance,
                    'description' => 'VOID: ' . $walletTransaction->description,
                    'reference_id' => 'VOID-' . $walletTransaction->id,
                ]);
            });
        }

        $walletTransaction->delete();

        return redirect()->route('admin.wallet-transactions.index')->with('success', 'Transaction voided and reversed successfully');
    }
}
