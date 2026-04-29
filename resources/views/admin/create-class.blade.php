@extends('layouts.app')

@section('title', 'Create Class - Admin')
@section('header', 'Create New Class')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Code -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Class Code *</label>
                <input type="text" name="code" value="{{ old('code') }}" required maxlength="20"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('code')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Class Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="4" 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Professor -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Assigned Professor *</label>
                <select name="professor_id" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                    <option value="">Select a professor...</option>
                    @foreach($professors as $professor)
                        <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
                            {{ $professor->name }}
                        </option>
                    @endforeach
                </select>
                @error('professor_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-700">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded font-semibold transition">
                    Create Class
                </button>
                <a href="{{ route('admin.classes') }}" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 rounded font-semibold text-center transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
