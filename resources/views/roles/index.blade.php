@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 relative">
    <!-- Create Role Button at the Top-Right Corner -->
    @can('role-create')
    <div class="flex justify-end mb-4">
        <a href="{{ route('roles.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-300">
            + Create New Role
        </a>
    </div>
    @endcan

    <h1 class="text-4xl font-bold mb-8 text-center text-gray-800">Roles List</h1>

    @if($roles->count())
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="py-4 px-6 text-left">ID</th>
                        <th class="py-4 px-6 text-left">Name</th>
                        <th class="py-4 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm divide-y divide-gray-200">
                    @foreach($roles as $role)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">{{ $role->id }}</td>
                            <td class="py-4 px-6 font-semibold">{{ $role->name }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center space-x-2">
                                    @can('role-list')
                                    <a href="{{ route('roles.show', $role->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-xs font-medium transition duration-200">
                                        View
                                    </a>
                                    @endcan

                                    @can('role-edit')
                                    <a href="{{ route('roles.edit', $role->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white py-1 px-3 rounded text-xs font-medium transition duration-200">
                                        Edit
                                    </a>
                                    @endcan
                                    
                                    @can('role-delete')
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-xs font-medium transition duration-200">
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
        <p class="text-center text-gray-500 mt-8">No roles found.</p>
    @endif
</div>
@endsection
