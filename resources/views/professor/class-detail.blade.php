@extends('layouts.app')

@section('title', $classe->name . ' - Class Detail')
@section('header', $classe->name)

@section('content')
<div class="p-6 space-y-6">
    <!-- Class Header -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $classe->name }}</h1>
                <p class="text-gray-400 mt-2">{{ $classe->code }}</p>
                @if($classe->description)
                    <p class="text-gray-300 mt-3">{{ $classe->description }}</p>
                @endif
            </div>
            <span class="inline-block px-4 py-2 bg-green-900/30 text-green-300 rounded-lg font-semibold">
                {{ $classe->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-900 border border-gray-800 p-4 rounded-lg">
            <p class="text-gray-400 text-sm">Total Students</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $classe->students->count() }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-4 rounded-lg">
            <p class="text-gray-400 text-sm">Schedules</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $classe->schedules->count() }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-4 rounded-lg">
            <p class="text-gray-400 text-sm">Attendance Records</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $classe->attendanceRecords()->count() }}</p>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="border-b border-gray-800 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Enrolled Students</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Student ID</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Name</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Email</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Enrolled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($classe->students as $student)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-white">{{ $student->student_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-white">{{ $student->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $student->email }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $student->pivot->enrolled_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">No students enrolled yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Schedules -->
    @if($classe->schedules->count() > 0)
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="border-b border-gray-800 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Class Schedules</h2>
        </div>
        <div class="divide-y divide-gray-800">
            @foreach($classe->schedules as $schedule)
                <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-800/50 transition">
                    <div>
                        <p class="text-white font-semibold">{{ $schedule->days }}</p>
                        <p class="text-gray-400 text-sm">{{ $schedule->time }} • Room {{ $schedule->room }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
