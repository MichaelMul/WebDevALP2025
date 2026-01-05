<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Admin
     */
    public function index()
    {
        // Mengambil data statistik untuk ditampilkan di dashboard
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCouriers = Courier::count();
        $totalCustomers = Customer::count();
        
        // Mengambil user admin untuk menyapa
        $adminName = auth()->user()->name;

        // Mengirim data ke view 'admin.dashboard'
        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'totalCouriers', 
            'totalCustomers',
            'adminName'
        ));
    }
}
