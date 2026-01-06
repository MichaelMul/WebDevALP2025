@extends('layouts.app')

@section('title', 'Order Details - Kaya Boys')

@section('content')
<div style="background: #FFFBF0; padding: 4rem 2rem; min-height: 100vh;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('orders.index') }}" style="color: #D97706; text-decoration: none; font-weight: 600;">← Back to Orders</a>
        </div>

        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $order->order_number }}</h1>
                    <p style="color: #666;">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                
                @php
                    $statusColors = [
                        'pending' => '#F59E0B',
                        'confirmed' => '#3B82F6',
                        'preparing' => '#8B5CF6',
                        'delivering' => '#06B6D4',
                        'completed' => '#10B981',
                        'cancelled' => '#EF4444'
                    ];
                    $statusColor = $statusColors[$order->order_status] ?? '#6B7280';
                @endphp
                <span style="background: {{ $statusColor }}; color: white; padding: 0.6rem 1.5rem; border-radius: 20px; font-size: 1rem; font-weight: 600; text-transform: capitalize;">
                    {{ $order->order_status }}
                </span>
            </div>

            <!-- Order Items -->
            <div style="border-top: 1px solid #F0F0F0; padding-top: 1.5rem; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">Order Items</h2>
                
                @foreach($order->orderItems as $item)
                <div style="display: flex; gap: 1rem; padding: 1rem; background: #FFFBF0; border-radius: 8px; margin-bottom: 1rem;">
                    <img src="{{ $item->product->image_url ?? asset('images/products/sandwich.jpg') }}" alt="{{ $item->product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px;">
                    
                    <div style="flex: 1;">
                        <h3 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.3rem;">{{ $item->product->name }}</h3>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 0.5rem;">{{ $item->product->description }}</p>
                        <div style="color: #666;">Quantity: {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                    </div>

                    <div style="text-align: right; padding-top: 0.5rem;">
                        <div style="color: #D97706; font-weight: 700; font-size: 1.1rem;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Delivery Information -->
            <div style="border-top: 1px solid #F0F0F0; padding-top: 1.5rem; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">Delivery Information</h2>
                <div style="background: #FFFBF0; padding: 1rem; border-radius: 8px;">
                    <div style="margin-bottom: 0.5rem;"><strong>Address:</strong></div>
                    <div style="color: #666; margin-bottom: 1rem;">{{ $order->delivery_address }}</div>
                    
                    @if($order->notes)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #F0E5C9;">
                        <div style="margin-bottom: 0.5rem;"><strong>Notes:</strong></div>
                        <div style="color: #666;">{{ $order->notes }}</div>
                    </div>
                    @endif

                    @if($order->delivery && $order->order_status === 'delivered')
                    <!-- Delivery Confirmation & Rating -->
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #10B981; background: #F0FDF4; padding: 1.5rem; border-radius: 8px; margin: 1.5rem -1rem -1rem;">
                        <div style="text-align: center; margin-bottom: 1rem;">
                            <div style="font-size: 3rem; margin-bottom: 0.5rem;">✅</div>
                            <h3 style="font-size: 1.3rem; font-weight: 700; color: #166534; margin-bottom: 0.5rem;">Order Delivered!</h3>
                            <p style="color: #166534; font-size: 0.95rem;">Did you receive your order?</p>
                        </div>

                        @php
                            $hasRating = \App\Models\Delivery::where('id', $order->delivery->id)
                                ->whereNotNull('customer_rating')
                                ->exists();
                        @endphp

                        @if(!$hasRating)
                        <form action="{{ route('orders.rate', $order) }}" method="POST" style="background: white; padding: 1.5rem; border-radius: 8px;">
                            @csrf
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-weight: 600; margin-bottom: 0.8rem; color: #1a1a1a; text-align: center;">Rate Your Courier</label>
                                <div id="starContainer" style="display: flex; justify-content: center; gap: 0.3rem; font-size: 2.5rem;">
                                    <input type="radio" name="rating" value="1" id="star1" style="display: none;" required>
                                    <label for="star1" class="star-label" data-value="1" style="cursor: pointer; user-select: none;">☆</label>
                                    
                                    <input type="radio" name="rating" value="2" id="star2" style="display: none;">
                                    <label for="star2" class="star-label" data-value="2" style="cursor: pointer; user-select: none;">☆</label>
                                    
                                    <input type="radio" name="rating" value="3" id="star3" style="display: none;">
                                    <label for="star3" class="star-label" data-value="3" style="cursor: pointer; user-select: none;">☆</label>
                                    
                                    <input type="radio" name="rating" value="4" id="star4" style="display: none;">
                                    <label for="star4" class="star-label" data-value="4" style="cursor: pointer; user-select: none;">☆</label>
                                    
                                    <input type="radio" name="rating" value="5" id="star5" style="display: none;">
                                    <label for="star5" class="star-label" data-value="5" style="cursor: pointer; user-select: none;">☆</label>
                                </div>
                                <div id="ratingText" style="text-align: center; margin-top: 0.5rem; color: #666; font-size: 0.9rem;">Click to rate</div>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Feedback (Optional)</label>
                                <textarea name="feedback" rows="3" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;" placeholder="Share your experience with the courier..."></textarea>
                            </div>

                            <button type="submit" style="width: 100%; background: #10B981; color: white; border: none; padding: 1rem; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem;">Submit Rating</button>
                        </form>
                        @else
                        <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px;">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $order->delivery->customer_rating)
                                        <span style="color: #F59E0B;">⭐</span>
                                    @else
                                        <span style="color: #D1D5DB;">⭐</span>
                                    @endif
                                @endfor
                            </div>
                            <p style="color: #666; font-size: 0.95rem;">Thank you for your feedback!</p>
                            @if($order->delivery->customer_feedback)
                            <div style="margin-top: 1rem; padding: 1rem; background: #FFFBF0; border-radius: 6px; font-style: italic; color: #666;">
                                "{{ $order->delivery->customer_feedback }}"
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

<script>
const texts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
const starLabels = document.querySelectorAll('.star-label');
const starContainer = document.getElementById('starContainer');
const ratingText = document.getElementById('ratingText');

// Add hover effect to show half and full stars
starLabels.forEach((label, index) => {
    label.addEventListener('mouseover', function() {
        updateStarDisplay(index + 1);
    });
    
    label.addEventListener('click', function() {
        setRating(index + 1);
    });
});

// Reset stars when mouse leaves container
starContainer.addEventListener('mouseleave', function() {
    const checkedStar = document.querySelector('input[name="rating"]:checked');
    if (checkedStar) {
        updateStarDisplay(parseInt(checkedStar.value));
    } else {
        starLabels.forEach(label => {
            label.textContent = '☆';
            label.style.color = '#D1D5DB';
        });
    }
});

function updateStarDisplay(rating) {
    starLabels.forEach((label, index) => {
        if (index < rating) {
            label.textContent = '★'; // Full star
            label.style.color = '#F59E0B';
        } else {
            label.textContent = '☆'; // Empty star
            label.style.color = '#D1D5DB';
        }
    });
}

function setRating(rating) {
    document.getElementById('star' + rating).checked = true;
    updateStarDisplay(rating);
    ratingText.textContent = texts[rating - 1];
    ratingText.style.color = '#F59E0B';
    ratingText.style.fontWeight = '600';
}
</script>

            <!-- Payment & Summary -->
            <div style="border-top: 1px solid #F0F0F0; padding-top: 1.5rem;">
                <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">Payment Details</h2>
                
                <div style="background: #FFFBF0; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span>Payment Method:</span>
                        <span style="font-weight: 600;">{{ $order->payment_method }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span>Payment Status:</span>
                        <span style="font-weight: 600; color: {{ $order->payment_status === 'paid' ? '#10B981' : '#F59E0B' }}; text-transform: capitalize;">
                            {{ $order->payment_status }}
                        </span>
                    </div>
                    
                    <div style="border-top: 1px solid #F0E5C9; margin-top: 1rem; padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #666;">Subtotal</span>
                            <span style="font-weight: 600;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="color: #666;">Delivery Fee</span>
                            <span style="font-weight: 600;">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.3rem;">
                            <span style="font-weight: 700;">Total</span>
                            <span style="color: #D97706; font-weight: 700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if(in_array($order->order_status, ['pending', 'confirmed']))
                <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    @csrf
                    <button type="submit" style="background: #FF6B6B; color: white; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancel Order</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div style="position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem 2rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="position: fixed; top: 20px; right: 20px; background: #FF6B6B; color: white; padding: 1rem 2rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    {{ session('error') }}
</div>
@endif
@endsection
