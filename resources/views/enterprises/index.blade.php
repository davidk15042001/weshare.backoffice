<x-app-layout>
<div class="px-6 py-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Enterprise Management</h1>

        {{-- <a href="{{ route('enterprises.create') }}"
           class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            + Add Enterprise
        </a> --}}
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-white shadow rounded-xl p-5 border">
            <p class="text-gray-500 text-sm">Total Enterprises</p>
            <h2 class="text-2xl font-bold mt-1">{{ $stats['total'] }}</h2>
        </div>

        <div class="bg-white shadow rounded-xl p-5 border">
            <p class="text-gray-500 text-sm">Total Employees</p>
            <h2 class="text-2xl font-bold mt-1">{{ $stats['employees'] }}</h2>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded-xl border overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-sm font-medium text-gray-700">
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Company</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Employees</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($enterprises as $enterprise)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-3">{{ $enterprise->name }}</td>
                    <td class="px-6 py-3">{{ $enterprise->company_name }}</td>
                    <td class="px-6 py-3">{{ $enterprise->email }}</td>
                    <td class="px-6 py-3">{{ $enterprise->phone_number }}</td>
                    <td class="px-6 py-3">{{ $enterprise->employees_count }}</td>

                    <td class="px-6 py-3 text-right space-x-4">
                        {{-- <a href="{{ route('enterprises.edit', $enterprise->id) }}"
                           class="text-blue-600 hover:underline">Edit</a> --}}

                        <form action="{{ route('enterprises.destroy', $enterprise->id) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline"
                                    onclick="return confirm('Delete this enterprise?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $enterprises->links() }}
    </div>

</div>
</x-app-layout>
