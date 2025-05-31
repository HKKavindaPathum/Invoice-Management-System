@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h2 class="text-2xl font-bold mb-6">Add Client</h2>

        <!-- Error Display -->
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <!-- Grid Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <select name="title" required 
                                class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                            <option value="">Select Title</option>
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Dr.">Dr.</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" required 
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" required 
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country"
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Passport Number</label>
                        <input type="text" name="passport_no" 
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" rows="3"
                                  class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <input type="text" name="company_name" 
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                        <input type="text" name="mobile_no" 
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $email) }}" 
                               onclick="this.value = '';" 
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                        <textarea name="note" rows="2"
                                  class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('clients.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                    Save Client
                </button>
            </div>
        </form>
    </div>
@endsection