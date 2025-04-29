@extends('layouts.app')

@section('content')
    <div class="mx-auto bg-white shadow-lg rounded-lg p-6 mt-1">
        <h1 class="text-2xl font-bold mb-4">Settings</h1>
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="app_name" class="block font-semibold">App Name:</label>
                    <input type="text" id="app_name" name="app_name" required value="{{ old('app_name', $settings->app_name ?? '') }}" class="w-full border p-2 rounded">
                </div>
                
                <div>
                    <label for="company_name" class="block font-semibold">Company Name:</label>
                    <input type="text" id="company_name" name="company_name" required value="{{ old('company_name', $settings->company_name ?? '') }}" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label for="company_phone" class="block font-semibold">Company Phone:</label>
                    <input type="text" id="company_phone" name="company_phone" required value="{{ old('company_phone', $settings->company_phone ?? '') }}" class="w-full border p-2 rounded">
                </div>
                
                <div>
                    <label for="country" class="block font-semibold">Country:</label>
                    <input type="text" id="country" name="country" value="{{ old('country', $settings->country ?? '') }}" class="w-full border p-2 rounded">
                </div>
                
                <div>
                    <label for="state" class="block font-semibold">State:</label>
                    <input type="text" id="state" name="state" value="{{ old('state', $settings->state ?? '') }}" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label for="city" class="block font-semibold">City:</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $settings->city ?? '') }}" class="w-full border p-2 rounded">
                </div>
                
                <div>
                    <label for="zip_code" class="block font-semibold">Zip Code:</label>
                    <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code', $settings->zip_code ?? '') }}" class="w-full border p-2 rounded">
                </div>
                
                <div>
                    <label for="fax_number" class="block font-semibold">Fax Number:</label>
                    <input type="text" id="fax_number" name="fax_number" value="{{ old('fax_number', $settings->fax_number ?? '') }}" class="w-full border p-2 rounded">
                </div>
                
                <div class="col-span-2">
                    <label for="company_address" class="block font-semibold">Company Address:</label>
                    <textarea id="company_address" name="company_address" required class="w-full border p-2 rounded">{{ old('company_address', $settings->company_address ?? '') }}</textarea>
                </div>
                
                <div>
                    <label for="app_logo" class="block font-semibold">App Logo:</label>
                    @if ($settings && $settings->app_logo)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Current Logo:</p>
                            <img src="{{ asset( $settings->app_logo) }}" alt="Current App Logo" class="h-16 w-auto">
                        </div>
                    @endif
                    <input type="file" id="app_logo" name="app_logo" class="w-full border p-2">
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Settings</button>
            </div>
        </form>
        
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("Settings page loaded");
    });
</script>
@endpush
