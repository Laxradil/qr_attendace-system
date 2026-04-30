@extends('layouts.professor')

@section('title', 'Activity Logs - Professor')
@section('header', 'My Activity Logs')

@section('content')
<div class="p-6 space-y-6">
    <!-- Logs List -->
    <div class="space-y-3">
        @forelse($logs as $log)
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-4 hover:border-gray-700 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="inline-block px-3 py-1 bg-purple-900/30 text-purple-300 text-xs rounded-full font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </div>
                    <span class="text-gray-500 text-sm">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @if($log->description)
                    <p class="text-gray-300 text-sm mt-2">{{ $log->description }}</p>
                @endif
                <div class="flex gap-4 text-gray-500 text-xs mt-3 pt-3 border-t border-gray-800">
                    @if($log->ip_address)
                        <span>IP: {{ $log->ip_address }}</span>
                    @endif
                    <span>{{ $log->created_at->format('M d, Y H:i:s') }}</span>
                </div>
            </div>
        @empty
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-12 text-center">
                <p class="text-gray-400">No activity logs yet</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="flex justify-center">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
