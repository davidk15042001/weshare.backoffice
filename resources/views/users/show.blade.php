<x-app-layout>
    <div class="px-6 py-10 space-y-8">

        {{-- ===== USER PROFILE ===== --}}
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">User Profile</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-700">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>User Id:</strong> {{ $user->id }}</p>
                <p><strong>Stripe Customer Id:</strong> {{ $user->stripe_id }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="px-2 py-1 text-xs rounded text-white
                        {{ $user->subscription ? 'bg-green-600' : 'bg-gray-500' }}">
                        {{ $user->subscription ? 'Subscribed' : 'Free User' }}
                    </span>
                </p>

                <p><strong>Date Joined:</strong> {{ $user->created_at }}</p>
            </div>
        </div>

        {{-- ===== SUBSCRIPTION DETAILS ===== --}}
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Subscriptions</h2>

            @forelse($subscriptions as $sub)
                <div class="p-4 mb-4 rounded-xl bg-gray-50 border">
                    <div class="grid md:grid-cols-2 gap-3 text-gray-700">
                        <p><strong>Plan:</strong> {{ $sub->name }}</p>
                        <p><strong>Amount:</strong> {{ app_currency(). number_format($sub->price * $sub->quantity, 2) }}</p>
                        <p><strong>Start:</strong> {{ $sub->updated_at }}</p>
                        <p><strong>End:</strong> {{ $sub->ends_at }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No subscription records found</p>
            @endforelse
        </div>

        {{-- ===== SAVED CARDS ===== --}}
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Saved Cards</h2>

            @forelse($cards as $card)
                {{-- <div class="p-4 mb-4 bg-gray-50 rounded-xl border flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ strtoupper($card->brand) }} •••• {{ $card->last4 }}</p>
                        <p class="text-gray-600 text-sm">Expires: {{ $card->exp_month }}/{{ $card->exp_year }}</p>
                    </div>
                </div> --}}
            @empty
                <p class="text-gray-500">No saved cards available</p>
            @endforelse
        </div>

        {{-- ===== TRANSACTIONS ===== --}}
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Transactions</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="py-2">Amount</th>
                            <th>Status</th>
                            <th>Narration</th>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700">
                        @foreach($transactions as $tx)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 font-semibold">
                                    {{app_currency(). number_format($tx->amount, 2) }}
                                </td>
                                <td>
                                    <span class="px-2 py-1 rounded text-white text-xs
                                        {{ $tx->status=='success' ? 'bg-green-600' : 'bg-gray-600' }}">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </td>
                                <td>{{ $tx->narration ?? '—' }}</td>
                                <td class="font-mono">{{ $tx->transaction_code }}</td>
                                <td>{{ $tx->created_at }}</td>
                                <td>
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 text-lg">
                                        <i class="fa fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
