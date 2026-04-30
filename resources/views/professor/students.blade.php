@extends('layouts.app')

@section('title', 'Students - Professor')
@section('header', 'My Students')

@section('content')
<div class="p-6 space-y-6">
    <!-- Students Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Student ID</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Name</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Email</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Classes</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-white font-mono">{{ $student->student_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-white">{{ $student->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $student->email }}</td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $student->enrolledClasses->count() }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-green-900/30 text-green-300 text-xs rounded-full font-semibold">
                                    {{ $student->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
