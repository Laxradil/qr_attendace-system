@extends('layouts.app')

@section('title', 'Users Management - Admin')
@section('header', 'Users Management')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-white">All Users</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition">
            + Add User
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Name</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Email</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Username</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Role</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Status</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-white">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $user->username }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-purple-900/30 text-purple-300 text-xs rounded-full font-semibold capitalize">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 {{ $user->is_active ? 'bg-green-900/30 text-green-300' : 'bg-red-900/30 text-red-300' }} text-xs rounded-full font-semibold">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-400 hover:text-blue-300 font-semibold text-sm">Edit</a>
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 font-semibold text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="flex justify-center">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
