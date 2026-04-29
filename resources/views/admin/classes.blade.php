@extends('layouts.app')

@section('title', 'Classes Management - Admin')
@section('header', 'Classes Management')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-white">All Classes</h2>
        <a href="{{ route('admin.classes.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition">
            + Add Class
        </a>
    </div>

    <!-- Classes Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Code</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Name</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Professor</th>
                        <th class="px-6 py-3 text-center text-gray-300 font-semibold text-sm">Students</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Status</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($classes as $classe)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-white font-semibold">{{ $classe->code }}</td>
                            <td class="px-6 py-4 text-white">{{ $classe->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $classe->professor->name }}</td>
                            <td class="px-6 py-4 text-center text-gray-400">{{ $classe->students->count() }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 {{ $classe->is_active ? 'bg-green-900/30 text-green-300' : 'bg-red-900/30 text-red-300' }} text-xs rounded-full font-semibold">
                                    {{ $classe->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('admin.classes.edit', $classe) }}" class="text-blue-400 hover:text-blue-300 font-semibold text-sm">Edit</a>
                                <form action="{{ route('admin.classes.delete', $classe) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 font-semibold text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">No classes found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($classes->hasPages())
        <div class="flex justify-center">
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection
