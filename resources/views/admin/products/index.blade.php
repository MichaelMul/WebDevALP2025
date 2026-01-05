@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Manage Products</h2>
                <button onclick="document.getElementById('createModal').style.display='flex'" 
                        style="background: #3b82f6; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    + Add Product
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Products Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Image</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Name</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Description</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Category</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Price</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Stock</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem 1.5rem;">
                            @if($product->image_url)
                                <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" 
                                     style="width: 48px; height: 48px; object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb;">
                            @else
                                <div style="width: 48px; height: 48px; background: #f3f4f6; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                    📦
                                </div>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; color: #374151; font-weight: 600;">{{ $product->name }}</td>
                        <td style="padding: 1rem 1.5rem; color: #6b7280; max-width: 300px;">
                            {{ Str::limit($product->description, 60) }}
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            @if($product->category)
                                <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center; color: #374151; font-weight: 600;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            @if($product->is_available)
                                <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    ✓ Available
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    ✗ Unavailable
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            <button onclick="editProduct({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->description) }}', {{ $product->price }}, {{ $product->category_id ?? 'null' }}, {{ $product->is_available ? 'true' : 'false' }})"
                                    style="background: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 4px; border: none; margin-right: 0.5rem; cursor: pointer; font-size: 0.875rem;">
                                Edit
                            </button>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Delete {{ $product->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; padding: 0.5rem 1rem; border-radius: 4px; border: none; cursor: pointer; font-size: 0.875rem;">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #9ca3af;">
                            No products found. Click "+ Add Product" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Create Modal -->
<div id="createModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 90%; max-width: 600px; margin: 2rem auto; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">Add New Product</h3>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Name</label>
                <input type="text" name="name" required 
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Description</label>
                <textarea name="description" required rows="3" 
                          style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;"></textarea>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Price (Rp)</label>
                <input type="number" name="price" required min="0" step="1000"
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Category</label>
                <select name="category_id" 
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    <option value="">-- None --</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Image</label>
                <input type="file" name="image" accept="image/*"
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_available" value="1" checked
                           style="width: 16px; height: 16px; margin-right: 0.5rem;">
                    <span style="font-weight: 600; color: #374151;">Available for purchase</span>
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('createModal').style.display='none'" 
                        style="background: #6b7280; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="background: #3b82f6; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 90%; max-width: 600px; margin: 2rem auto; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">Edit Product</h3>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Name</label>
                <input type="text" id="editName" name="name" required 
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Description</label>
                <textarea id="editDescription" name="description" required rows="3" 
                          style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;"></textarea>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Price (Rp)</label>
                <input type="number" id="editPrice" name="price" required min="0" step="1000"
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Category</label>
                <select id="editCategory" name="category_id" 
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    <option value="">-- None --</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Image (leave empty to keep current)</label>
                <input type="file" name="image" accept="image/*"
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" id="editAvailable" name="is_available" value="1"
                           style="width: 16px; height: 16px; margin-right: 0.5rem;">
                    <span style="font-weight: 600; color: #374151;">Available for purchase</span>
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" 
                        style="background: #6b7280; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="background: #f59e0b; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editProduct(id, name, description, price, categoryId, isAvailable) {
    document.getElementById('editForm').action = '/admin/products/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editPrice').value = price;
    document.getElementById('editCategory').value = categoryId || '';
    document.getElementById('editAvailable').checked = isAvailable;
    document.getElementById('editModal').style.display = 'flex';
}

// Close modals when clicking outside
document.getElementById('createModal').onclick = function(e) {
    if (e.target.id === 'createModal') this.style.display = 'none';
}
document.getElementById('editModal').onclick = function(e) {
    if (e.target.id === 'editModal') this.style.display = 'none';
}
</script>
@endsection
