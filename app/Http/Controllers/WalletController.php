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
}
