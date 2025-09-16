@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Edit User</h1>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}"
                    class="block w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2"
                    required>
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}"
                    class="block w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2"
                    required>
            </div>
            
            <!-- Role Selector - Multiple -->
            <div>
                <label for="roles" class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                <select name="roles[]" id="roles" multiple
                    class="block w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2 h-auto"
                    required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" 
                            {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                <small class="text-gray-500">Hold Ctrl/Cmd to select multiple roles</small>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="password" id="password"
                    class="block w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2">
                <small class="text-gray-500">Leave blank if you don't want to change password.</small>
            </div>
            
            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="block w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition">
                    Save Changes
                </button>
            </div>

            <!-- Back Link -->
            <div class="text-center">
                <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-blue-600 mt-4 inline-block">
                    ← Back to users
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
