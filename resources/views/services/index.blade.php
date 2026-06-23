@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 space-y-6">
    <div class="bg-white border border-slate-100/80 shadow-[0_2px_12px_rgba(0,0,0,0.015)] rounded-2xl p-6">

        <!-- Search + Add Service -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <!-- Search Form -->
            <form action="{{ route('services.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-l-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition duration-200 text-sm"
                    placeholder="Search services..." 
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

            <!-- Add Service Button -->
            @can('service-create')
            <a href="{{ route('services.create') }}" 
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md shadow-blue-500/10 transition duration-200 text-sm w-full md:w-auto mt-2 md:mt-0">
                Add Service
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

        <!-- Desktop Table -->
        @can('service-list')
        <div class="hidden md:block overflow-hidden border border-slate-100 rounded-xl">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">Service Name</th>
                        <th class="py-3.5 px-4 font-semibold">Category</th>
                        <th class="py-3.5 px-4 font-semibold">Price</th>
                        <th class="py-3.5 px-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($services as $service)
                    <tr class="hover:bg-slate-50/30 transition duration-150">

                        <td class="py-4 px-4 font-semibold text-slate-900">
                            <a href="{{ route('services.show', $service->id) }}" class="hover:text-slate-700 hover:underline transition">
                                {{ $service->name }}
                            </a>
                        </td>
                        <td class="py-4 px-4 text-slate-600 font-medium">{{ $service->category->name }}</td>
                        <td class="py-4 px-4 font-semibold text-slate-800">RS: {{ number_format($service->unit_price, 2) }}</td>
                        <td class="py-4 px-4 flex justify-center items-center gap-3">
                            @can('service-edit')
                            <a href="{{ route('services.edit', $service->id) }}" class="text-slate-400 hover:text-slate-900 transition-colors duration-200">
                                <x-far-edit class="h-4.5 w-4.5" />
                            </a>
                            @endcan
                            @can('service-delete')
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?')" class="inline-flex items-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors duration-200">
                                    <x-gmdi-delete class="h-5 w-5" />
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @foreach($services as $service)
            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">

                    <div class="flex flex-col">
                        <a href="{{ route('services.show', $service->id) }}" class="font-medium text-blue-600 hover:text-blue-800">
                            {{ $service->name }}
                        </a>
                        <span class="text-gray-500 text-sm">{{ $service->category->name }}</span>
                        <span class="text-gray-700 font-semibold">RS: {{ number_format($service->unit_price, 2) }}</span>
                    </div>
                </div>
                <div class="flex gap-2 mt-2 sm:mt-0">
                    @can('service-edit')
                    <a href="{{ route('services.edit', $service->id) }}" class="text-blue-500 hover:text-blue-700">
                        <x-far-edit class="h-5 w-5" />
                    </a>
                    @endcan
                    @can('service-delete')
                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
</div>
@endsection

@push('scripts')
<script>
    function clearSearch() {
        window.location.href = '{{ route('services.index') }}';
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
