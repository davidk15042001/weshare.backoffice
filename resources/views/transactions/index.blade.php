<x-app-layout>
    <div class="px-6 py-6">

        {{-- PAGE TITLE --}}
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Transactions Overview</h1>

        {{-- ANALYTICS CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div class="bg-white shadow rounded-xl p-5 border">
                <p class="text-gray-500 text-sm">Total Transactions</p>
                <h2 class="text-2xl font-bold mt-1">{{ $stats['total'] }}</h2>
            </div>

            <div class="bg-white shadow rounded-xl p-5 border">
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <h2 class="text-2xl font-bold mt-1">{{ app_currency() . number_format($stats['revenue'], 2) }}</h2>
            </div>

            <div class="bg-white shadow rounded-xl p-5 border">
                <p class="text-gray-500 text-sm">Successful Transactions</p>
                <h2 class="text-2xl font-bold mt-1 text-green-600">{{ $stats['success'] }}</h2>
            </div>

            <div class="bg-white shadow rounded-xl p-5 border">
                <p class="text-gray-500 text-sm">Failed Transactions</p>
                <h2 class="text-2xl font-bold mt-1 text-red-600">{{ $stats['failed'] }}</h2>
            </div>

        </div>

        {{-- FILTERS --}}
        <div class="bg-white p-6 shadow rounded-xl border mb-8">
            <form method="GET" class="flex gap-6">

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg">
                        <option value="">All</option>
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Date From</label>
                    <input type="date" name="date_from" class="w-full border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Date To</label>
                    <input type="date" name="date_to" class="w-full border-gray-300 rounded-lg">
                </div>

                <div class="flex items-end">
                    <button type="submit" name="filter" value="1" class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                        Apply Filters
                    </button>
                    <div class="flex items-center gap-3 ml-5">

                    <button type="submit" name="export_type" value="excel"
                        class="px-5 py-2 border border-green-600 text-green-600 rounded-lg shadow hover:bg-green-600 hover:text-white transition">
                        Export Excel
                    </button>

                    <button type="submit" name="export_type" value="pdf"
                        class="px-5 py-2 border border-red-600 text-red-600 rounded-lg shadow hover:bg-red-600 hover:text-white transition">
                        Export PDF
                    </button>

                </div>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white shadow rounded-xl border overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-sm font-medium text-gray-700">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Reference</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="text-sm">
                @foreach ($transactions as $t)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $t->id }}</td>
                        <td class="px-6 py-3">{{ $t->name }}</td>
                        <td class="px-6 py-3">{{ $t->transaction_code }}</td>
                        <td class="px-6 py-3">{{ app_currency() . number_format($t->amount, 2) }}</td>

                        <td class="px-6 py-3">
                            <span class="
                                px-3 py-1 text-xs font-semibold rounded-full
                                @if($t->status == 'success') bg-green-100 text-green-700
                                @elseif($t->status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif
                            ">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-3">{{ $t->created_at }}</td>

                        <td class="px-6 py-3 text-right space-x-5">
                            <a href="{{ route('transactions.show', $t->id) }}"
                               class="text-blue-600 hover:underline">
                                <i class="fa fa-folder-o"></i>View</a>

                            <a href="{{ route('transactions.print', $t->id) }}"
                               class="text-red-600 hover:underline">
                                <i class="fa fa-print"></i>Print</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $transactions->links() }}
        </div>

    </div>
</x-app-layout>
