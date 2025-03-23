@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">Edit Client</h2>

        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Use PUT method for updates -->

            <!-- Grid Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <select name="title" required 
                                class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                            <option value="">Select Title</option>
                            <option value="Mr." {{ old('title', $client->title) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                            <option value="Mrs." {{ old('title', $client->title) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            <option value="Ms." {{ old('title', $client->title) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            <option value="Dr." {{ old('title', $client->title) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        </select>
                    </div>


                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" required 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                               value="{{ old('first_name', $client->first_name) }}">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" required 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                               value="{{ old('last_name', $client->last_name) }}">
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Country</label>
                        <input type="text" name="country"
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                               value="{{ old('country', $client->country) }}">
                    </div>
                    
                    <!-- Passport No -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Passport No</label>
                        <input type="text" name="passport_no" 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                               value="{{ old('passport_no', $client->passport_no) }}">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address"
                                  class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">{{ old('address', $client->address) }}</textarea>
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input type="text" name="company_name"
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                               value="{{ old('company_name', $client->company_name) }}">
                    </div>

                    <!-- Mobile No -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mobile No</label>
                        <input type="text" name="mobile_no"
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                               value="{{ old('mobile_no', $client->mobile_no) }}">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email"
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                               value="{{ old('email', $client->email) }}">
                    </div>

                    <!-- Note -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Note</label>
                        <textarea name="note"
                                  class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">{{ old('note', $client->note) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Buttons Container -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    Update
                </button>
                <a href="{{ route('clients.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg ml-2 hover:bg-gray-600 transition duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection  
