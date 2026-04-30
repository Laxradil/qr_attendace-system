@extends('layouts.app')

@section('title', 'Schedules - Professor')
@section('header', 'Class Schedules')

@section('content')
<div class="p-6 space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($schedules as $schedule)
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $schedule->subject_name }}</h3>
                        <p class="text-gray-400 text-sm mt-1">{{ $schedule->subject_code }}</p>
                    </div>
                    <span class="text-gray-500 text-2xl font-bold">{{ $schedule->room }}</span>
                </div>
                
                <div class="space-y-3 border-t border-gray-700 pt-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Days:</span>
                        <span class="text-white font-semibold">{{ $schedule->days }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Time:</span>
                        <span class="text-white font-semibold">{{ $schedule->time }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Professor:</span>
                        <span class="text-white font-semibold">{{ $schedule->professor }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-700 flex gap-3">
                    <button class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded font-semibold transition">
                        View Details
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-900 border border-gray-800 rounded-lg p-12 text-center">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-400">No schedules available</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
