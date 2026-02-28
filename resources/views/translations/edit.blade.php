<x-app-layout>
    <div class="py-6 px-6 max-w-4xl mx-auto">

        {{-- PAGE TITLE --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Edit Translation
        </h1>

        {{-- FORM CARD --}}
        <form method="POST"
              action="{{ route('translations.update', $translation->id) }}"
              class="bg-white shadow rounded-lg p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- KEY --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Key (Value)
                </label>
                <input
                    type="text"
                    name="value"
                    value="{{ old('value', $translation->default) }}"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            {{-- ENGLISH --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    English Text
                </label>
                <textarea
                    name="en"
                    rows="4"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('en', $translation->translation['en'] ?? '') }}</textarea>
            </div>

            {{-- GERMAN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    German Text
                </label>
                <textarea
                    name="de"
                    rows="4"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('de', $translation->translation['de'] ?? '') }}</textarea>
            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                    Update
                </button>

                <a href="{{ route('translations.index') }}"
                   class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</x-app-layout>
