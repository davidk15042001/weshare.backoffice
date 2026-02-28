<x-app-layout>
    <div class="py-6 px-6 max-w-7xl mx-auto">

        {{-- PAGE HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Translations
            </h1>
            <a href="{{ route('translations.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                + Add Translation
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="">
                <form class="text-right">
                    <input type="text" name="search" class="p-1.5 block m-2 border-gray-200 border rounded-sm w-1/2" placeholder="Search ..." />
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Key
                            </th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                English
                            </th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                German
                            </th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-600">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($translations as $item)
                            @php
                                $trans = is_array($item->translation)
                                    ? $item->translation
                                    : json_decode($item->translation, true);
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $item->default }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $trans['en'] ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $trans['de'] ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-right flex space-x-2">
                                    <a href="{{ route('translations.edit', $item->id) }}"
                                       class="inline  px-3 py-1.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200">
                                        Edit
                                    </a>

                                    <form action="{{ route('translations.destroy', $item->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Delete this translation?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-red-100 text-red-700 rounded hover:bg-red-200">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-6 text-center text-gray-500">
                                    No translations found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $translations->links() }}
        </div>

    </div>
</x-app-layout>
