@extends('layouts.app')

@section('title', 'Attendance Records - Admin')
@section('header', 'All Attendance Records')

@section('content')
<div class="p-6 space-y-6">
    <!-- Attendance Records Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Student</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Class</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Date & Time</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Status</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Minutes Late</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($records as $record)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-white">{{ $record->student->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $record->classe->code }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $record->recorded_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $record->status === 'present' ? 'bg-green-900/30 text-green-300' : '' }}
                                    {{ $record->status === 'late' ? 'bg-amber-900/30 text-amber-300' : '' }}
                                    {{ $record->status === 'absent' ? 'bg-red-900/30 text-red-300' : '' }}
                                ">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $record->status === 'late' ? $record->minutes_late . ' min' : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No attendance records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($records->hasPages())
        <div class="flex justify-center">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
