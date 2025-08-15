@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 relative">
    <!-- Create New User Button -->
    @can('user-create')
    <div class="flex justify-end mb-6">
        <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300">
            + Create New User
        </a>
    </div>
    @endcan

    <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Users List</h1>

    @if($users->count())
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                    <tr>
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Role</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-6">{{ $user->id }}</td>
                            <td class="py-3 px-6">{{ $user->name }}</td>
                            <td class="py-3 px-6">{{ $user->email }}</td>
                            <td class="py-3 px-6">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->getRoleNames() as $role)
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                            {{ $role }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex justify-center space-x-2">
                                    @can('user-list')
                                    <a href="{{ route('users.show', $user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded text-xs transition duration-300">
                                        View
                                    </a> 
                                    @endcan

                                    @can('user-edit')
                                    <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded text-xs transition duration-300">
                                        Edit
                                    </a>
                                    @endcan

                                    @can('user-delete')
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded text-xs transition duration-300">
                                            Delete
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
    @else
        <p class="text-center text-gray-500 mt-6">No users found.</p>
    @endif
</div>
@endsection
