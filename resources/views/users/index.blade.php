<x-app-layout>
    <div class="px-6 py-8">

        {{-- ===== CARDS ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-sm text-gray-500">Total Users</h3>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>

            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-sm text-gray-500">Free Users</h3>
                <p class="text-3xl font-bold">{{ $totalFree }}</p>
            </div>

            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-sm text-gray-500">Subscribed Users</h3>
                <p class="text-3xl font-bold">{{ $totalSubscribed }}</p>
            </div>

        </div>

        {{-- ===== FILTER FORM ===== --}}
        <form class="flex items-center gap-4 mb-6">
            <input
                name="search"
                value="{{ $search }}"
                placeholder="Search name or email..."
                class="border rounded px-3 py-2 w-60"
            />

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="free" {{ $status=='free'?'selected':'' }}>Free</option>
                <option value="subscribed" {{ $status=='subscribed'?'selected':'' }}>Subscribed</option>
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </form>

        {{-- ===== USERS TABLE ===== --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-center">
                        <th class="py-2">Name</th>
                        <th>Email</th>
                        <th>Subscription Status</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $u)
                        <tr class="border-b text-center">
                            <td class="py-2">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="px-2 py-1 text-xs rounded text-white
                                    {{ $u->subscription ? 'bg-green-600' : 'bg-gray-500' }}">
                                    {{ $u->subscription ? 'Subscribed' : 'Free' }}
                                </span>
                            </td>
                            <td>{{ $u->created_at }}</td>
                            <td>
                                <span class="px-2 py-1 text-xs rounded text-white
                                    {{ $u->deactivated_at ? 'bg-red-600' : 'bg-green-500' }}">
                                    {{ $u->deactivated_at ? 'Deactivated' : 'Active' }}
                                </span>
                            </td>

                            <td>
                                <a
                                    href="{{ route('users.show', $u->id) }}"
                                    class="text-blue-600 font-semibold">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
