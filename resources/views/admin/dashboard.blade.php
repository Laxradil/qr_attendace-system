@extends('layouts.app')

@section('title', 'Dashboard - Admin')
@section('header', 'Admin Dashboard')

@section('content')
<div class="p-6 space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Total Users</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalUsers }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Professors</p>
            <p class="text-3xl font-bold text-purple-400 mt-2">{{ $totalProfessors }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Students</p>
            <p class="text-3xl font-bold text-blue-400 mt-2">{{ $totalStudents }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Classes</p>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ $totalClasses }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Attendance Records</p>
            <p class="text-3xl font-bold text-amber-400 mt-2">{{ $totalAttendance }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.users.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Add User
        </a>
        <a href="{{ route('admin.classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Class
        </a>
        <a href="{{ route('admin.qr-codes') }}" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            QR Codes
        </a>
        <a href="{{ route('admin.logs') }}" class="bg-red-600 hover:bg-red-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            System Logs
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4">Recent Activity</h2>
        <div class="space-y-3">
            @forelse($recentLogs as $log)
                <div class="flex items-start justify-between p-3 hover:bg-gray-800/50 rounded transition">
                    <div>
                        <p class="text-white text-sm font-semibold">{{ $log->user->name ?? 'System' }}</p>
                        <p class="text-gray-400 text-xs">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</p>
                    </div>
                    <span class="text-gray-500 text-xs">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No recent activity</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
