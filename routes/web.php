<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CourierDashboardController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PointsHistoryController;
use App\Http\Controllers\CancellationController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/menu', function () {
    $products = Product::where('is_available', true)->get();
    return view('menu', compact('products'));
})->name('menu');

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect based on role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'courier') {
        // Check if courier record exists
        if (!$user->courier) {
            // If courier record doesn't exist, redirect to home with error
            return redirect('/')->with('error', 'Courier profile not found. Please contact support.');
        }
        return redirect()->route('courier.dashboard');
    }
    
    // Default customer dashboard
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Cart routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/rate', [OrderController::class, 'rate'])->name('orders.rate');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wallet routes
    Route::post('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
    Route::get('/wallet/history', [WalletController::class, 'history'])->name('wallet.history');
});

// --- COURIER/DRIVER ROUTES ---
Route::middleware(['auth'])->prefix('courier')->name('courier.')->group(function () {
    Route::get('/dashboard', [CourierDashboardController::class, 'index'])->name('dashboard');
    Route::get('/deliveries', [CourierDashboardController::class, 'myDeliveries'])->name('deliveries');
    Route::post('/accept-order/{order}', [CourierDashboardController::class, 'acceptOrder'])->name('accept-order');
    Route::post('/delivery/{delivery}/update-status', [CourierDashboardController::class, 'updateStatus'])->name('update-status');
    Route::post('/toggle-availability', [CourierDashboardController::class, 'toggleAvailability'])->name('toggle-availability');
});

// --- KHUSUS ADMIN (CRUD LENGKAP) ---
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // CRUD Resources
    Route::resource('admin/users', UserController::class, ['as' => 'admin']);
    Route::resource('admin/products', ProductController::class, ['as' => 'admin']);
    Route::resource('admin/categories', CategoryController::class, ['as' => 'admin']);
    Route::resource('admin/couriers', CourierController::class, ['as' => 'admin']);
    Route::resource('admin/customers', CustomerController::class, ['as' => 'admin']);
    Route::resource('admin/deliveries', DeliveryController::class, ['as' => 'admin']);
    
    // Orders management
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/admin/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/admin/orders/{order}', [OrderController::class, 'adminUpdate'])->name('admin.orders.update');
    
    // Wallet Transactions management
    Route::get('/admin/wallet-transactions', [WalletController::class, 'index'])->name('admin.wallet-transactions.index');
    Route::get('/admin/wallet-transactions/create', [WalletController::class, 'create'])->name('admin.wallet-transactions.create');
    Route::post('/admin/wallet-transactions', [WalletController::class, 'store'])->name('admin.wallet-transactions.store');
    Route::get('/admin/wallet-transactions/{walletTransaction}', [WalletController::class, 'show'])->name('admin.wallet-transactions.show');
    Route::get('/admin/wallet-transactions/{walletTransaction}/edit', [WalletController::class, 'edit'])->name('admin.wallet-transactions.edit');
    Route::put('/admin/wallet-transactions/{walletTransaction}', [WalletController::class, 'adminUpdate'])->name('admin.wallet-transactions.update');
    Route::delete('/admin/wallet-transactions/{walletTransaction}', [WalletController::class, 'destroy'])->name('admin.wallet-transactions.destroy');
    
    // Cart Items management
    Route::get('/admin/cart-items', [CartController::class, 'adminIndex'])->name('admin.cart-items.index');
    Route::get('/admin/cart-items/{cartItem}', [CartController::class, 'show'])->name('admin.cart-items.show');
    Route::delete('/admin/cart-items/{cartItem}', [CartController::class, 'adminDestroy'])->name('admin.cart-items.destroy');
    Route::post('/admin/cart-items/clear-abandoned', [CartController::class, 'clearAbandoned'])->name('admin.cart-items.clear-abandoned');
    
    // New CRUD Resources
    Route::resource('admin/order-items', OrderItemController::class, ['as' => 'admin']);
    Route::resource('admin/transactions', TransactionController::class, ['as' => 'admin']);
    Route::resource('admin/order-tracking', OrderTrackingController::class, ['as' => 'admin']);
    Route::resource('admin/points-history', PointsHistoryController::class, ['as' => 'admin']);
    Route::resource('admin/cancellations', CancellationController::class, ['as' => 'admin']);
});

require __DIR__.'/auth.php';
