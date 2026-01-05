<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // Function Index (Biarkan saja, jarang dipakai kalau sudah full di menu)
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // Tidak dipakai lagi karena sudah pakai Modal Popup
    }

    // ==========================================
    // 1. FUNCTION SIMPAN (STORE)
    // ==========================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan ke folder public/images/products
            $file->move(public_path('images/products'), $filename);
            $validated['image_url'] = 'images/products/' . $filename;
        }

        Product::create($validated);

        // PERBAIKAN DISINI: Pakai back() agar tetap di halaman Menu
        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        // Tidak dipakai lagi karena sudah pakai Modal Popup
    }

    // ==========================================
    // 2. FUNCTION UPDATE
    // ==========================================
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image_url && File::exists(public_path($product->image_url))) {
                File::delete(public_path($product->image_url));
            }
            
            // Upload gambar baru
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $validated['image_url'] = 'images/products/' . $filename;
        }

        $product->update($validated);

        // PERBAIKAN DISINI: Pakai back() agar tetap di halaman Menu
        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    // ==========================================
    // 3. FUNCTION HAPUS (DESTROY)
    // ==========================================
    public function destroy(Product $product)
    {
        // Hapus gambar dari folder
        if ($product->image_url && File::exists(public_path($product->image_url))) {
            File::delete(public_path($product->image_url));
        }
        
        $product->delete();

        // PERBAIKAN DISINI: Pakai back() agar tetap di halaman Menu
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
