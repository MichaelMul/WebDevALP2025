<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Products (All-in-One)
        </h2>
    </x-slot>

    {{-- PARENT STATE UNTUK MODAL CREATE --}}
    <div class="py-12" x-data="{ openCreate: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- HEADER & TOMBOL ADD --}}
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Product List</h3>
                    {{-- Tombol ini memicu Modal Create --}}
                    <button @click="openCreate = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                        + Add New Product
                    </button>
                </div>

                {{-- TABEL PRODUK --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-bold text-gray-600 uppercase">Image</th>
                                <th class="py-3 px-6 text-left text-xs font-bold text-gray-600 uppercase">Details</th>
                                <th class="py-3 px-6 text-left text-xs font-bold text-gray-600 uppercase">Price</th>
                                <th class="py-3 px-6 text-center text-xs font-bold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($products as $product)
                            {{-- STATE UNTUK MODAL EDIT (Setiap baris punya state sendiri) --}}
                            <tr class="border-b border-gray-200 hover:bg-gray-50" x-data="{ openEdit: false }">
                                
                                {{-- Kolom Gambar --}}
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($product->image_url)
                                            <img class="w-12 h-12 rounded border object-cover" src="{{ asset($product->image_url) }}" />
                                        @else
                                            <div class="w-12 h-12 rounded border bg-gray-100 flex items-center justify-center text-xs text-gray-400">No img</div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Nama & Deskripsi --}}
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-900 block">{{ $product->name }}</span>
                                    <span class="text-xs text-gray-500">{{ Str::limit($product->description, 30) }}</span>
                                    <div class="mt-1">
                                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $product->is_available ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Kolom Harga --}}
                                <td class="py-3 px-6 text-left font-bold text-orange-600">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>

                                {{-- Kolom Aksi --}}
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center gap-2">
                                        {{-- Tombol Edit (Buka Modal) --}}
                                        <button @click="openEdit = true" class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 flex items-center justify-center transition">
                                            ✏️
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete {{ $product->name }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>

                                    {{-- ====================== --}}
                                    {{-- MODAL EDIT (POPUP) --}}
                                    {{-- ====================== --}}
                                    <div x-show="openEdit" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            {{-- Overlay Gelap --}}
                                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openEdit = false"></div>

                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            
                                            {{-- Kotak Modal --}}
                                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                                                    @csrf @method('PUT')
                                                    
                                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Product</h3>
                                                    
                                                    <div class="space-y-4 text-left">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Name</label>
                                                            <input type="text" name="name" value="{{ $product->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Price</label>
                                                            <input type="number" name="price" value="{{ $product->price }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Category</label>
                                                            <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                                {{-- Pastikan controller mengirim $categories --}}
                                                                @foreach(\App\Models\Category::all() as $cat)
                                                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Description</label>
                                                            <textarea name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $product->description }}</textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Change Image</label>
                                                            <input type="file" name="image" class="mt-1 block w-full text-sm">
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm">
                                                            <span class="ml-2 text-sm text-gray-600">Available for Order</span>
                                                        </div>
                                                    </div>

                                                    <div class="mt-5 sm:mt-6 flex justify-end gap-2">
                                                        <button type="button" @click="openEdit = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded font-bold hover:bg-gray-400">Cancel</button>
                                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END MODAL EDIT --}}

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400">
                                    No products found. Click "Add New Product" to start.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- ====================== --}}
        {{-- MODAL CREATE (POPUP) --}}
        {{-- ====================== --}}
        <div x-show="openCreate" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openCreate = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Product</h3>
                        
                        <div class="space-y-4 text-left">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required placeholder="Ex: Beef Burger">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                                <input type="number" name="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required placeholder="25000">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    {{-- Mengambil kategori langsung dari Model untuk Form Create --}}
                                    @foreach(\App\Models\Category::all() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Image</label>
                                <input type="file" name="image" class="mt-1 block w-full text-sm">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-600">Available for Order</span>
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-6 flex justify-end gap-2">
                            <button type="button" @click="openCreate = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded font-bold hover:bg-gray-400">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
