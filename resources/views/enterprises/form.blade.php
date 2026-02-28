<x-app-layout>
<div class="px-6 py-6 max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">
        {{ isset($enterprise) ? 'Edit Enterprise' : 'Create Enterprise' }}
    </h1>

    <form method="POST"
        action="{{ isset($enterprise) ? route('enterprises.update', $enterprise->id) : route('enterprises.store') }}"
        class="space-y-6 bg-white p-6 shadow rounded-xl border">

        @csrf
        @if(isset($enterprise))
            @method('PUT')
        @endif

        <input type="text" name="name" placeholder="Name"
               value="{{ old('name', $enterprise->name ?? '') }}"
               class="w-full border-gray-300 rounded-lg">

        <input type="text" name="company_name" placeholder="Company Name"
               value="{{ old('company_name', $enterprise->company_name ?? '') }}"
               class="w-full border-gray-300 rounded-lg">

        <input type="email" name="email" placeholder="Email"
               value="{{ old('email', $enterprise->email ?? '') }}"
               class="w-full border-gray-300 rounded-lg">

        <input type="text" name="phone_number" placeholder="Phone Number"
               value="{{ old('phone_number', $enterprise->phone_number ?? '') }}"
               class="w-full border-gray-300 rounded-lg">

        <input type="number" name="employees_count" placeholder="Amount of Employees"
               value="{{ old('employees_count', $enterprise->employees_count ?? '') }}"
               class="w-full border-gray-300 rounded-lg">

        <button class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ isset($enterprise) ? 'Update' : 'Create' }}
        </button>
    </form>

</div>
</x-app-layout>
