@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')

@section('content')
<div class="p-6 space-y-6">
    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $classe)
            <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden hover:border-purple-500 transition">
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4">
                    <h3 class="text-xl font-bold text-white">{{ $classe->code }}</h3>
                    <p class="text-gray-200 text-sm mt-1">{{ $classe->name }}</p>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Students:</span>
                        <span class="text-white font-semibold">{{ $classe->students_count }}</span>
                    </div>
                    @if($classe->description)
                        <p class="text-gray-400 text-sm line-clamp-2">{{ $classe->description }}</p>
                    @endif
                    <div class="flex items-center justify-between pt-3 border-t border-gray-800">
                        <span class="inline-block px-3 py-1 bg-green-900/30 text-green-300 text-xs rounded-full">
                            {{ $classe->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <a href="{{ route('professor.class-detail', $classe) }}" class="text-purple-500 hover:text-purple-400 font-semibold text-sm">
                            View →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-900 border border-gray-800 rounded-lg p-12 text-center">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.5S6.5 28.747 12 28.747s10-4.745 10-10.247S17.5 6.253 12 6.253z"></path>
                </svg>
                <p class="text-gray-400">No classes assigned yet</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
