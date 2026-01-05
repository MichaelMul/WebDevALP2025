<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cancellations Management') }}
            </h2>
            <a href="{{ route('admin.cancellations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Cancellation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.cancellations.index') }}" class="flex gap-4">
                        <select name="cancelled_by" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">All Cancellers</option>
                            <option value="customer" {{ request('cancelled_by') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ request('cancelled_by') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="courier" {{ request('cancelled_by') == 'courier' ? 'selected' : '' }}>Courier</option>
                        </select>

                        <select name="refund_status" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">All Refund Status</option>
                            <option value="pending" {{ request('refund_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ request('refund_status') == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="failed" {{ request('refund_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filter
                        </button>
                        <a href="{{ route('admin.cancellations.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancelled By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refund Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refund Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            @foreach($cancellations as $cancellation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cancellation->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.orders.show', $cancellation->order_id) }}" class="text-blue-600 hover:text-blue-900">
                                            #{{ $cancellation->order_id }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($cancellation->cancelled_by == 'customer') bg-blue-100 text-blue-800
                                            @elseif($cancellation->cancelled_by == 'admin') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($cancellation->cancelled_by) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ Str::limit($cancellation->reason, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($cancellation->refund_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($cancellation->refund_status == 'processed') bg-green-100 text-green-800
                                            @elseif($cancellation->refund_status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($cancellation->refund_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cancellation->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.cancellations.show', $cancellation) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="{{ route('admin.cancellations.edit', $cancellation) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.cancellations.destroy', $cancellation) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $cancellations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
