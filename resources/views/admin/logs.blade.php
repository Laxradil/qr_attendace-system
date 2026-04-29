@extends('layouts.app')

@section('title', 'System Logs - Admin')
@section('header', 'System Logs')

@section('content')
<div class="p-6 space-y-6">
    <!-- System Logs -->
    <div class="space-y-3">
        @forelse($logs as $log)
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-4 hover:border-gray-700 transition">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-900/30 rounded-full flex items-center justify-center">
                            <span class="text-purple-300 font-semibold text-xs">{{ substr($log->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $log->user->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $log->user->email }}</p>
                        </div>
                    </div>
                    <span class="text-gray-500 text-sm">{{ $log->created_at->diffForHumans() }}</span>
                </div>

                <div class="ml-13">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-block px-3 py-1 bg-purple-900/30 text-purple-300 text-xs rounded-full font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </div>
                    @if($log->description)
                        <p class="text-gray-300 text-sm">{{ $log->description }}</p>
                    @endif
                    <div class="flex gap-4 text-gray-500 text-xs mt-3 pt-3 border-t border-gray-800">
                        @if($log->ip_address)
                            <span>🌐 {{ $log->ip_address }}</span>
                        @endif
                        <span>⏱ {{ $log->created_at->format('M d, Y H:i:s') }}</span>
                    </div>
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
