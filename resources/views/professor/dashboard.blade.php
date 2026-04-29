@extends('layouts.app')

@section('title', 'Dashboard - Professor')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Professor Dashboard</h1>
            <p class="text-gray-400 mt-2">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('professor.scan-qr') }}" class="bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-lg font-semibold transition">
            Scan QR Code
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Classes -->
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Classes</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalClasses }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.5S6.5 28.747 12 28.747s10-4.745 10-10.247S17.5 6.253 12 6.253z"></path>
                </svg>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Students</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalStudents }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM12.93 12H0v7a6 6 0 0015.806-1M16 16a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Avg Attendance</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ isset($attendanceStats['present']) ? round(($attendanceStats['present'] / (($attendanceStats['present'] ?? 0) + ($attendanceStats['late'] ?? 0) + ($attendanceStats['absent'] ?? 0))) * 100) : 0 }}%</p>
                </div>
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Today's Classes -->
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Today's Classes</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ count($todaySchedules) }}</p>
                </div>
                <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    @if(count($todaySchedules) > 0)
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4">Today's Schedule</h2>
        <div class="space-y-3">
            @foreach($todaySchedules as $schedule)
            <div class="flex items-center justify-between bg-gray-800 p-4 rounded-lg border border-gray-700">
                <div>
                    <p class="text-white font-semibold">{{ $schedule->subject_name }}</p>
                    <p class="text-gray-400 text-sm">{{ $schedule->subject_code }} • Room {{ $schedule->room }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white font-semibold">{{ $schedule->time }}</p>
                    <p class="text-gray-400 text-sm">{{ $schedule->days }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('professor.classes') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.5S6.5 28.747 12 28.747s10-4.745 10-10.247S17.5 6.253 12 6.253z"></path>
            </svg>
            My Classes
        </a>
        <a href="{{ route('professor.attendance-records') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Attendance Records
        </a>
        <a href="{{ route('professor.reports') }}" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Reports
        </a>
    </div>
</div>
@endsection
