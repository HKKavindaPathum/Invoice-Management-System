@extends('layouts.app')

@section('content')
<div class="mx-auto bg-white shadow-xl rounded-xl p-6 mt-6 max-w-5xl">

    <h1 class="text-2xl font-bold mb-6">Settings</h1>

    <!-- Success Toast -->
    @if(session('success'))
    <div id="successToast" class="fixed top-16 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 opacity-0 transform translate-y-4 transition-all duration-500">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="app_name" class="block font-semibold mb-1">App Name:</label>
                <input type="text" id="app_name" name="app_name" required 
                    value="{{ old('app_name', $settings->app_name ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div>
                <label for="company_name" class="block font-semibold mb-1">Company Name:</label>
                <input type="text" id="company_name" name="company_name" required 
                    value="{{ old('company_name', $settings->company_name ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div>
                <label for="company_phone" class="block font-semibold mb-1">Company Phone:</label>
                <input type="text" id="company_phone" name="company_phone" required 
                    value="{{ old('company_phone', $settings->company_phone ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div>
                <label for="country" class="block font-semibold mb-1">Country:</label>
                <input type="text" id="country" name="country" 
                    value="{{ old('country', $settings->country ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div>
                <label for="state" class="block font-semibold mb-1">State:</label>
                <input type="text" id="state" name="state" 
                    value="{{ old('state', $settings->state ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div>
                <label for="city" class="block font-semibold mb-1">City:</label>
                <input type="text" id="city" name="city" 
                    value="{{ old('city', $settings->city ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div>
                <label for="zip_code" class="block font-semibold mb-1">Zip Code:</label>
                <input type="text" id="zip_code" name="zip_code" 
                    value="{{ old('zip_code', $settings->zip_code ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div>
                <label for="fax_number" class="block font-semibold mb-1">Fax Number:</label>
                <input type="text" id="fax_number" name="fax_number" 
                    value="{{ old('fax_number', $settings->fax_number ?? '') }}" 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">
            </div>

            <div class="md:col-span-2">
                <label for="company_address" class="block font-semibold mb-1">Company Address:</label>
                <textarea id="company_address" name="company_address" required 
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200">{{ old('company_address', $settings->company_address ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="app_logo" class="block font-semibold mb-1">App Logo:</label>
                @if ($settings && $settings->app_logo)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Current Logo:</p>
                        <img src="{{ asset($settings->app_logo) }}" alt="Current App Logo" class="h-16 w-auto rounded">
                    </div>
                @endif
                <input type="file" id="app_logo" name="app_logo" class="w-full border border-gray-300 p-2 rounded-lg">
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-full shadow-lg transition duration-300">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Success Toast Animation
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 5000);
        }
    });
</script>
@endpush
