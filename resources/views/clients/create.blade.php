@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">Add Client</h2>

        <!-- Error Popup -->
        @if (session('error'))
            <script>
                window.onload = function() {
                    alert("{{ session('error') }}");
                };
            </script>
        @endif

        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

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
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Dr.">Dr.</option>
                        </select>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" required 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" required 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Country</label>
                        <input type="text" name="country"
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Passport Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Passport Number</label>
                        <input type="text" name="passport_no" 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">


                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" 
                                  class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"></textarea>
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input type="text" name="company_name" 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
                        <input type="text" name="mobile_no" 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $email) }}" 
                               onclick="this.value = '';" 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>


                    <!-- Note -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Note</label>
                        <textarea name="note" 
                                  class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Buttons Container -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    Save
                </button>
                <a href="{{ route('clients.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg ml-2 hover:bg-gray-600 transition duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
