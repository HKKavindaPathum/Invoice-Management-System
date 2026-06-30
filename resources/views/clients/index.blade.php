@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 space-y-6">
    <div class="bg-white border border-slate-100/80 shadow-[0_2px_12px_rgba(0,0,0,0.015)] rounded-2xl p-6">

        <!-- Search + Add Client -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 relative">
            <!-- Search Form -->
            <form action="{{ route('clients.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-l-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition duration-200 text-sm"
                    placeholder="Search clients..." 
                    value="{{ request()->get('search') }}">

                <!-- Clear Icon -->
                @if(request()->get('search'))
                    <button 
                        type="button" 
                        onclick="clearSearch()" 
                        class="absolute right-28 top-3 text-slate-400 hover:text-slate-600 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif  

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-r-xl shadow-sm transition duration-200 text-sm outline-none">
                    Search
                </button>
            </form>

            <!-- Add Client Button -->
            @can('client-create')
            <a href="{{ route('clients.create') }}" 
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md shadow-blue-500/10 transition duration-200 text-sm w-full md:w-auto mt-2 md:mt-0">
                Add Client
            </a>
            @endcan
        </div>

        <!-- Success Toast -->
        @if(session('success'))
        <div id="successToast" class="fixed top-20 right-6 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl z-50 opacity-0 transform translate-y-4 transition-all duration-500 flex items-center gap-2 text-sm border border-slate-800">
            <span class="text-emerald-400">✓</span>
            {{ session('success') }}
        </div>
        @endif

        @can('client-list')
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-hidden border border-slate-100 rounded-xl">
            <table class="w-full table-fixed border-collapse">
                <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="w-1/3 py-3.5 px-6 text-left font-semibold">Client</th>
                        <th class="w-1/6 py-3.5 px-6 text-center font-semibold">Invoices</th>
                        <th class="w-1/3 py-3.5 px-6 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($clients as $client)
                    <tr class="hover:bg-slate-50/30 transition duration-150">
                        <td class="py-4 px-6 text-left">
                            <a href="{{ route('clients.show', $client->id) }}" class="text-slate-900 hover:text-slate-700 hover:underline font-semibold transition">
                                {{ $client->title }} {{ $client->first_name }} {{ $client->last_name }}<br>
                                <span class="text-xs text-slate-400 font-normal">{{ $client->email }}</span>
                            </a>
                        </td>
                        <td class="py-4 px-6 text-center font-semibold text-slate-800">
                            <a href="{{ route('invoices.index', ['client_id' => $client->id]) }}" class="text-blue-600 hover:text-blue-800 transition">
                                {{ $client->invoices_count }}
                            </a>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex items-center gap-4 justify-center">
                                @can('client-edit')
                                <a href="{{ route('clients.edit', $client->id) }}" class="text-slate-400 hover:text-slate-900 transition-colors duration-200">
                                    <x-far-edit class="h-4.5 w-4.5" />
                                </a>
                                @endcan
                                @can('client-delete')
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?')" class="inline-flex items-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors duration-200">
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

    {{-- Pagination --}}
    @if($clients->hasPages())
        <div class="mt-6 px-2">
            {{ $clients->links() }}
        </div>
    @endif
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
