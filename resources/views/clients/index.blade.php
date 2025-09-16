@extends('layouts.app')

@section('content')
<div class="mx-auto bg-white shadow-xl rounded-xl p-6 mt-6 max-w-7xl">

    <!-- Search + Add Client -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 relative">
        <!-- Search Form -->
        <form action="{{ route('clients.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
            <input 
                type="text" 
                name="search" 
                class="w-full px-4 py-2 border border-gray-300 rounded-l-full shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200"
                placeholder="Search clients..." 
                value="{{ request()->get('search') }}">

            <!-- Clear Icon -->
            @if(request()->get('search'))
                <button 
                    type="button" 
                    onclick="clearSearch()" 
                    class="absolute right-28 top-2 text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif  

            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-6 rounded-r-full shadow-md transition duration-200">
                Search
            </button>
        </form>

        <!-- Add Client Button -->
        @can('client-create')
        <a href="{{ route('clients.create') }}" 
            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow-lg transition duration-300 mt-4 md:mt-0">
            Add Client
        </a>
        @endcan
    </div>

    <!-- Success Toast -->
    @if(session('success'))
    <div id="successToast" class="fixed top-16 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 opacity-0 transform translate-y-4 transition-all duration-500">
        {{ session('success') }}
    </div>
    @endif

    @can('client-list')
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full table-fixed border-collapse shadow-md rounded-xl overflow-hidden">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <tr>
                    <th class="w-1/3 py-3 px-6 text-left">Client</th>
                    <th class="w-1/6 py-3 px-6 text-center">Invoice</th>
                    <th class="w-1/3 py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr class="border-b hover:bg-gray-50 transition duration-150">
                    <td class="py-3 px-6 text-left">
                        <a href="{{ route('clients.show', $client->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $client->title }} {{ $client->first_name }} {{ $client->last_name }}<br>
                            {{ $client->email }}
                        </a>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('invoices.index', ['client_id' => $client->id]) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $client->invoices_count }}
                        </a>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="inline-flex items-center gap-4 justify-center">
                            @can('client-edit')
                            <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:text-blue-700 transition duration-200">
                                <x-far-edit class="h-5 w-5" />
                            </a>
                            @endcan
                            @can('client-delete')
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?')" class="inline-flex items-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition duration-200">
                                    <x-gmdi-delete class="h-5 w-5" />
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @foreach($clients as $client)
        <div class="bg-white shadow-md rounded-lg p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <a href="{{ route('clients.show', $client->id) }}" class="font-medium text-blue-600 hover:text-blue-800 text-lg">
                    {{ $client->title }} {{ $client->first_name }} {{ $client->last_name }}
                </a>
                <div class="text-gray-500 text-sm">{{ $client->email }}</div>
                <div class="text-gray-500 text-sm">Invoices: {{ $client->invoices_count }}</div>
            </div>
            <div class="flex gap-2 mt-2 sm:mt-0">
                @can('client-edit')
                <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:text-blue-700">
                    <x-far-edit class="h-5 w-5" />
                </a>
                @endcan

                @can('client-delete')
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">
                        <x-gmdi-delete class="h-5 w-5" />
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @endforeach
    </div>
    @endcan
</div>
@endsection

@push('scripts')
<script>
    function clearSearch() {
        window.location.href = '{{ route('clients.index') }}';
    }

    document.addEventListener('DOMContentLoaded', function () {
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
