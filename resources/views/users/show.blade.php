@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl bg-white shadow-2xl rounded-3xl p-6 sm:p-8 md:p-10">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-center text-gray-800 mb-10">User Details</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Name -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl shadow-sm">
                <h2 class="text-sm sm:text-base md:text-lg font-bold text-blue-800 uppercase mb-1">Name</h2>
                <p class="text-base sm:text-lg md:text-xl font-semibold text-blue-900 break-words">{{ $user->name }}</p>
            </div>

            <!-- Email -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
                <h2 class="text-sm sm:text-base md:text-lg font-bold text-green-800 uppercase mb-1">Email</h2>
                <p class="text-base sm:text-lg md:text-xl font-semibold text-green-900 break-words">{{ $user->email }}</p>
            </div>
        </div>

        <!-- Roles -->
        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-xl shadow-sm mb-10">
            <h2 class="text-sm sm:text-base md:text-lg font-bold text-purple-800 uppercase mb-2">Roles</h2>
            <div class="flex flex-wrap gap-2">
                @forelse($user->roles as $role)
                    <span class="bg-purple-200 text-purple-900 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                        {{ ucfirst($role->name) }}
                    </span>
                @empty
                    <span class="text-xs sm:text-sm text-gray-500">No roles assigned.</span>
                @endforelse
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('users.edit', $user->id) }}" class="w-full text-center py-3 px-6 bg-blue-600 text-white text-sm sm:text-base font-semibold rounded-lg hover:bg-blue-700 transition">
                Edit User
            </a>

            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="w-full">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Are you sure you want to delete this user?')" class="w-full py-3 px-6 bg-red-600 text-white text-sm sm:text-base font-semibold rounded-lg hover:bg-red-700 transition">
                    Delete User
                </button>
            </form>
        </div>

        <!-- Back link -->
        <div class="mt-6 text-center">
            <a href="{{ route('users.index') }}" class="text-sm sm:text-base text-indigo-600 hover:text-indigo-700 font-semibold">
                ← Back to Users List
            </a>
        </div>
    </div>
</div>
@endsection
