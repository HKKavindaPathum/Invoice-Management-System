@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl p-8 sm:p-10 space-y-8">
        <!-- Title -->
        <h1 class="text-3xl sm:text-4xl font-extrabold text-center text-gray-800 mb-6">Role Details</h1>

        <!-- Role Name -->
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-700">Role Name</h2>
            <p class="text-lg text-gray-600 mt-1 border rounded-md px-4 py-2 bg-gray-50 shadow-sm">{{ $role->name }}</p>
        </div>

        <!-- Permissions -->
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Permissions</h2>
            @if($role->permissions->count())
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-600 list-disc list-inside">
                    @foreach($role->permissions as $permission)
                        <li class="text-lg">{{
                            $permission->name
                        }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 italic">No permissions assigned.</p>
            @endif
        </div>

        <!-- Back Button -->
        <div class="text-center pt-6">
            <a href="{{ route('roles.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline transition duration-300">
                ← Back to Roles
            </a>
        </div>
    </div>
</div>
@endsection
