@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f3f4f6; padding: 2rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Header -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Manage Categories</h2>
                <button onclick="document.getElementById('createModal').style.display='block'" 
                        style="background: #9333ea; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    + Add Category
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Categories Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">ID</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Name</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Description</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem 1.5rem; color: #374151;">{{ $category->id }}</td>
                        <td style="padding: 1rem 1.5rem; color: #374151; font-weight: 600;">{{ $category->name }}</td>
                        <td style="padding: 1rem 1.5rem; color: #6b7280;">{{ $category->description ?? '-' }}</td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            <button onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')"
                                    style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 4px; border: none; margin-right: 0.5rem; cursor: pointer; font-size: 0.875rem;">
                                Edit
                            </button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Delete this category?');">
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
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #9ca3af;">
                            No categories found. Click "+ Add Category" to create one.
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
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 90%; max-width: 500px; margin: 2rem auto;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">Add New Category</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Name</label>
                <input type="text" name="name" required 
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Description</label>
                <textarea name="description" rows="3" 
                          style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('createModal').style.display='none'" 
                        style="background: #6b7280; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="background: #9333ea; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 90%; max-width: 500px; margin: 2rem auto;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937;">Edit Category</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Name</label>
                <input type="text" id="editName" name="name" required 
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Description</label>
                <textarea id="editDescription" name="description" rows="3" 
                          style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" 
                        style="background: #6b7280; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="background: #3b82f6; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, name, description) {
    document.getElementById('editForm').action = '/admin/categories/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
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
