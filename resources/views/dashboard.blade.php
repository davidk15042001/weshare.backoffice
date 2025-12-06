<x-app-layout>
    <div class="py-10 px-6 space-y-8">

        {{-- ===== METRICS CARDS ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
                <h3 class="text-sm text-gray-500">Total Users</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalUsers }}</p>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm text-gray-500">Total Revenue</h3>

                    {{-- FILTER --}}
                    <form method="GET" class="text-xs">
                        <select name="filter" onchange="this.form.submit()" class="border-gray-300 text-sm rounded px-2 py-1">
                            <option value="all" {{ $filter=='all'?'selected':'' }}>All</option>
                            <option value="today" {{ $filter=='today'?'selected':'' }}>Today</option>
                            <option value="week" {{ $filter=='week'?'selected':'' }}>This Week</option>
                            <option value="month" {{ $filter=='month'?'selected':'' }}>This Month</option>
                        </select>
                    </form>
                </div>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ app_currency(). number_format($totalRevenue, 2) }}</p>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
                <h3 class="text-sm text-gray-500">Completed Transactions</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $completedTransactions }}</p>
            </div>
        </div>

        {{-- ===== CHARTS SECTION ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PIE CHART --}}
            <div class="p-6 bg-white rounded-2xl shadow-md">
                <h3 class="mb-4 font-semibold">Users Overview</h3>
                <div class="flex justify-center">
                    <canvas id="pieChart" style="max-width: 480px;max-height:200px;"></canvas>
                </div>
            </div>

            {{-- BAR CHART --}}
            <div class="p-6 bg-white rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold">Subscriptions Over Time</h3>

                    {{-- BAR CHART FILTER --}}
                    <form method="GET">
                        <select name="chart_range" onchange="this.form.submit()" class="border-gray-300 text-sm rounded px-2 py-1">
                            <option value="7"  {{ $chartRange==7?'selected':'' }}>Last 7 Days</option>
                            <option value="30" {{ $chartRange==30?'selected':'' }}>Last 30 Days</option>
                            <option value="365" {{ $chartRange==365?'selected':'' }}>This Year</option>
                        </select>
                    </form>
                </div>

                <canvas id="barChart"></canvas>
            </div>
        </div>

        {{-- ===== RECENT TRANSACTIONS ===== --}}
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <h3 class="text-lg font-semibold mb-4">Recent Transactions</h3>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-center bg-gray-100">
                        <th class="py-2">User</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Print</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recentTransactions as $tx)
                        <tr class="border-b text-center hover:bg-gray-50">
                            <td class="py-2">{{ $tx->name ?? 'N/A' }}</td>
                            <td>{{ app_currency().number_format($tx->amount, 2) }}</td>
                            <td>
                                <span class="px-2 py-1 rounded text-white text-xs
                                    {{ $tx->status=='success' ? 'bg-green-600' : 'bg-gray-500' }}">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td>{{ $tx->created_at }}</td>
                            <td class="gap-x-4">
                                <a href="{{ route('transactions.show', $tx->id) }}" class="p-2 text-lg text-indigo-600">
                                    <i class="fa fa-folder-o"></i>
                                </a>
                                <a href="{{ route('transactions.print', $tx->id) }}" class="p-2 text-lg text-red-600">
                                    <i class="fa fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>


    {{-- CHARTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // PIE
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Free Users', 'Subscribed Users'],
                datasets: [{
                    data: [{{ $freeUsers }}, {{ $subscribedUsers }}],
                    backgroundColor: ['#60a5fa', '#34d399']
                }]
            }
        });

        // BAR
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($subscriptionChart->pluck('date')) !!},
                datasets: [{
                    label: 'Subscriptions',
                    data: {!! json_encode($subscriptionChart->pluck('total')) !!},
                    backgroundColor: '#6366f1'
                }]
            }
        });
    </script>
</x-app-layout>
