@extends('layouts.professor')

@section('title', 'Reports - Professor')
@section('header', 'Attendance Reports')

@section('content')
<div class="p-6 space-y-6">
    <!-- Class Selection -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-4">
        <form class="flex gap-4">
            <div class="flex-1">
                <select name="class_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded px-3 py-2 focus:border-purple-500 outline-none">
                    <option value="">Select a class to view reports...</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ request('class_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->code }} - {{ $classe->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded font-semibold transition">
                View Report
            </button>
        </form>
    </div>

    @if($attendanceData)
        <!-- Report Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
                <p class="text-gray-400 text-sm">Total Students</p>
                <p class="text-3xl font-bold text-white mt-2">{{ count($attendanceData) }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
                <p class="text-gray-400 text-sm">Total Attendance Records</p>
                <p class="text-3xl font-bold text-white mt-2">{{ collect($attendanceData)->sum('total') }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
                <p class="text-gray-400 text-sm">Average Attendance Rate</p>
                <p class="text-3xl font-bold text-white mt-2">{{ round(collect($attendanceData)->avg('percentage')) }}%</p>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Student</th>
                            <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Student ID</th>
                            <th class="px-6 py-3 text-center text-gray-300 font-semibold text-sm">Total</th>
                            <th class="px-6 py-3 text-center text-gray-300 font-semibold text-sm">Present</th>
                            <th class="px-6 py-3 text-center text-gray-300 font-semibold text-sm">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($attendanceData as $data)
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-white">{{ $data['student']->name }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $data['student']->student_id ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center text-gray-400">{{ $data['total'] }}</td>
                                <td class="px-6 py-4 text-center text-green-300">{{ $data['present'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $data['percentage'] >= 80 ? 'bg-green-900/30 text-green-300' : '' }}
                                        {{ $data['percentage'] >= 60 && $data['percentage'] < 80 ? 'bg-amber-900/30 text-amber-300' : '' }}
                                        {{ $data['percentage'] < 60 ? 'bg-red-900/30 text-red-300' : '' }}
                                    ">
                                        {{ $data['percentage'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-gray-900 border border-gray-800 rounded-lg p-12 text-center">
            <p class="text-gray-400">Select a class to view attendance reports</p>
        </div>
    @endif
</div>
@endsection
