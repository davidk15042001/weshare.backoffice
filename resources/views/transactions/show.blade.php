<x-app-layout>
    <div class="px-6 py-6">

        {{-- PAGE HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Transaction Details</h1>
                <p class="text-gray-500 mt-1">Transaction #{{ $transaction->id }}</p>
            </div>

            <a href="{{ route('transactions.print', $transaction->id) }}"
               class="px-5 py-2 bg-red-600 text-white text-sm rounded-lg shadow hover:bg-red-700">
                <i class="fa fa-print"></i> Print Invoice
            </a>
        </div>



        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <div class="bg-white shadow rounded-xl border p-5">
                <p class="text-gray-500 text-sm">Amount</p>
                <h2 class="text-2xl font-bold mt-1">
                    {{  $transaction->currency  . number_format($transaction->amount, 2) }}
                </h2>
            </div>

            <div class="bg-white shadow rounded-xl border p-5">
                <p class="text-gray-500 text-sm">Gateway</p>
                <h2 class="text-xl font-semibold mt-1">{{ ucfirst($transaction->gateway) }}</h2>
            </div>

            <div class="bg-white shadow rounded-xl border p-5">
                <p class="text-gray-500 text-sm">Created At</p>
                <h2 class="text-xl font-semibold mt-1">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d H:i') }}
                </h2>
            </div>

            <div class="bg-white shadow rounded-xl border p-5">
                <p class="text-gray-500 text-sm">Status</p>
                <span class="
                    px-4 py-2 text-sm font-semibold rounded-full
                    @if($transaction->status == 'success') bg-green-100 text-green-700
                    @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-700
                    @else bg-red-100 text-red-700 @endif
                ">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>

        </div>



        {{-- USER INFO + TRANSACTION INFO  --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

            {{-- USER INFO --}}
            <div class="bg-white shadow rounded-xl border p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">User Information</h3>

                <div class="gap-y-3 gap-x-3 grid grid-cols-2 text-base">
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Name:</span> {{ $transaction->name }}</p>
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Email:</span> {{ $transaction->email }}</p>
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Stripe ID:</span> {{ $transaction->stripe_id ?? 'N/A' }}</p>
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Product UID:</span> {{ $transaction->product_uid }}</p>
                </div>
            </div>

            {{-- TRANSACTION DETAILS --}}
            <div class="bg-white shadow rounded-xl border p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Transaction Information</h3>

                <div class="gap-y-3 gap-x-3 grid grid-cols-2 md:grid-cols-3 text-base">
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Transaction Code:</span> {{ $transaction->transaction_code }}</p>
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Transaction Source:</span> {{ $transaction->transaction_source }}</p>
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Gateway Reference:</span> {{ $transaction->gateway_reference ?? 'N/A' }}</p>
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Quantity:</span> {{ $transaction->quantity }}</p>
                    <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Narration:</span> {{ $transaction->narration }}</p>
                </div>
            </div>

        </div>


        {{-- PRODUCT / PLAN DETAILS --}}
        <div class="bg-white shadow rounded-xl border p-6 mb-10">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Product / Plan Details</h3>

            <div class="gap-y-3 gap-x-3 grid grid-cols-2 md:grid-cols-4 text-base">
                <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Plan:</span> {{ $transaction->description }}</p>
                <p class="p-1 border-r border-gray-400"><span class="font-semibold text-gray-700">Price:</span> {{ app_currency() . number_format($transaction->price, 2) }}</p>
                <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Period:</span> {{ ucfirst($transaction->period) }}</p>
                <p class="p-1 py-2 border-r border-gray-400"><span class="font-semibold text-gray-700">Trial Days:</span> {{ $transaction->trial }}</p>
            </div>
        </div>


        {{-- META (IP + USER AGENT) --}}
        <div class="bg-white shadow rounded-xl border p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Meta Data</h3>

            @php
                $meta = json_decode($transaction->meta, true);
            @endphp

            <div class="space-y-3 text-sm">
                <p><span class="font-semibold text-gray-700">IP Address:</span> {{ $meta['ip'] ?? 'N/A' }}</p>
                <p>
                    <span class="font-semibold text-gray-700">User Agent:</span><br>
                    <span class="text-gray-600">{{ $meta['user_agent'] ?? 'N/A' }}</span>
                </p>
            </div>
        </div>

    </div>
</x-app-layout>
