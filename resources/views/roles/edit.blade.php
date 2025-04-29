@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl p-10 space-y-8">
        <h1 class="text-4xl font-extrabold text-center text-gray-800">Edit Role</h1>

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Role Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Role Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ $role->name }}"
                    class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none px-4 py-2 shadow-sm text-gray-700"
                    required
                >
            </div>

            <!-- Permissions -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Assign Permissions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center bg-gray-50 p-2 rounded-md shadow-sm hover:bg-blue-50 transition">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->id }}"
                                {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                class="text-blue-600 border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-300"
                            >
                            <span class="ml-3 text-gray-700 text-sm">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-md"
                >
                    Update Role
                </button>
            </div>

            <!-- Back Link -->
            <div class="text-center">
                <a href="{{ route('roles.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">
                    ← Back to roles
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
