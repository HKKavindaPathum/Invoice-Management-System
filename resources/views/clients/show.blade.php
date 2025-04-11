@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center bg-gradient-to-r from-blue-50 to-purple-50 p-6">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full">

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
                Client Information
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Client Name:</span>
                        <p class="text-lg font-medium text-gray-800">{{ $client->title }}{{ $client->first_name }} {{ $client->last_name }}</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Country:</span>
                        <p class="text-lg font-medium text-gray-800">{{ $client->country }}</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Passport No:</span>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $client->passport_no ?? 'No Passport No available' }}
                        </p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Address:</span>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $client->address ?? 'No Address available' }}
                        </p>
                    </div>

                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">

                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Company Name:</span>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $client->company_name ?? 'No Company Name available' }}
                        </p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Mobile No:</span>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $client->mobile_no ?? 'No Mobile No available' }}
                        </p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Email:</span>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $client->email ?? 'No Email available' }}
                        </p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Note:</span>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $client->note ?? 'No note available' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center space-x-4">
                <a href="{{ route('clients.edit', $client->id) }}" class= "bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Edit Client
                </a>
                <a href="{{ route('clients.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Back to Clients List
                </a>
            </div>
        </div>
    </div>
@endsection
