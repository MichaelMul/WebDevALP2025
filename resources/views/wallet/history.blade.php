@extends('layouts.app')

@section('title', 'Wallet History - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('profile.show') }}" style="background: #D97706; color: white; padding: 0.6rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">← Back to Profile</a>
            <h1 style="font-size: 2.5rem; color: #1a1a1a; margin: 0;">Wallet History</h1>
        </div>

        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            @if($transactions->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f0f0f0;">
                            <th style="text-align: left; padding: 1rem; font-weight: 700;">Date</th>
                            <th style="text-align: left; padding: 1rem; font-weight: 700;">Description</th>
                            <th style="text-align: left; padding: 1rem; font-weight: 700;">Type</th>
                            <th style="text-align: right; padding: 1rem; font-weight: 700;">Amount</th>
                            <th style="text-align: right; padding: 1rem; font-weight: 700;">Balance After</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 1rem; color: #666;">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </td>
                                <td style="padding: 1rem;">
                                    {{ $transaction->description }}
                                </td>
                                <td style="padding: 1rem;">
                                    <span style="
                                        padding: 0.4rem 0.8rem;
                                        border-radius: 6px;
                                        font-size: 0.9rem;
                                        font-weight: 600;
                                        @if($transaction->type === 'credit')
                                            background: #D1FAE5;
                                            color: #065F46;
                                        @else
                                            background: #FEE2E2;
                                            color: #7F1D1D;
                                        @endif
                                    ">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: 600;">
                                    <span style="@if($transaction->type === 'credit') color: #059669; @else color: #DC2626; @endif">
                                        @if($transaction->type === 'credit') + @else - @endif
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: 600; color: #1a1a1a;">
                                    Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    {{ $transactions->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 2rem;">
                    <p style="color: #666; font-size: 1.1rem;">No wallet transactions yet.</p>
                    <a href="{{ route('profile.show') }}" style="color: #D97706; font-weight: 600; text-decoration: none;">Start by topping up your wallet!</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
