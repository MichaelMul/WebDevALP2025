<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8">Welcome, {{ $adminName }}! 👋</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Products -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-blue-100 text-sm font-semibold">Total Products</p>
                                    <h3 class="text-4xl font-bold mt-2">{{ $totalProducts }}</h3>
                                </div>
                                <span class="text-4xl">📦</span>
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="text-blue-100 text-sm mt-4 inline-block hover:underline">Manage →</a>
                        </div>

                        <!-- Total Categories -->
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-6 shadow-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-purple-100 text-sm font-semibold">Total Categories</p>
                                    <h3 class="text-4xl font-bold mt-2">{{ $totalCategories }}</h3>
                                </div>
                                <span class="text-4xl">📂</span>
                            </div>
                            <a href="{{ route('admin.categories.index') }}" class="text-purple-100 text-sm mt-4 inline-block hover:underline">Manage →</a>
                        </div>

                        <!-- Total Couriers -->
                        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-green-100 text-sm font-semibold">Total Couriers</p>
                                    <h3 class="text-4xl font-bold mt-2">{{ $totalCouriers }}</h3>
                                </div>
                                <span class="text-4xl">🚚</span>
                            </div>
                            <a href="{{ route('admin.couriers.index') }}" class="text-green-100 text-sm mt-4 inline-block hover:underline">Manage →</a>
                        </div>

                        <!-- Total Customers -->
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg p-6 shadow-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-orange-100 text-sm font-semibold">Total Customers</p>
                                    <h3 class="text-4xl font-bold mt-2">{{ $totalCustomers }}</h3>
                                </div>
                                <span class="text-4xl">👥</span>
                            </div>
                            <a href="{{ route('admin.customers.index') }}" class="text-orange-100 text-sm mt-4 inline-block hover:underline">Manage →</a>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Quick Links</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('admin.products.index') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition">
                                <p class="text-sm text-gray-600">Manage Products</p>
                                <p class="text-lg font-bold text-gray-900">Products</p>
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition">
                                <p class="text-sm text-gray-600">Manage Categories</p>
                                <p class="text-lg font-bold text-gray-900">Categories</p>
                            </a>
                            <a href="{{ route('admin.couriers.index') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition">
                                <p class="text-sm text-gray-600">Manage Couriers</p>
                                <p class="text-lg font-bold text-gray-900">Couriers</p>
                            </a>
                            <a href="{{ route('admin.customers.index') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition">
                                <p class="text-sm text-gray-600">Manage Customers</p>
                                <p class="text-lg font-bold text-gray-900">Customers</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
