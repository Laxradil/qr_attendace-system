@extends('layouts.app')

@section('title', 'Edit Class - Admin')
@section('header', 'Edit Class: ' . $classe->name)

@section('content')
<div class="p-6 max-w-2xl">
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <form action="{{ route('admin.classes.update', $classe) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Code -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Class Code *</label>
                <input type="text" name="code" value="{{ $classe->code }}" required maxlength="20"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('code')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Class Name *</label>
                <input type="text" name="name" value="{{ $classe->name }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="4" 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">{{ $classe->description }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Professor -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Assigned Professor *</label>
                <select name="professor_id" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                    @foreach($professors as $professor)
                        <option value="{{ $professor->id }}" {{ $classe->professor_id == $professor->id ? 'selected' : '' }}>
                            {{ $professor->name }}
                        </option>
                    @endforeach
                </select>
                @error('professor_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Status</label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" {{ $classe->is_active ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-gray-300">Active</span>
                </label>
            </div>

            <!-- Class Info -->
            <div class="border-t border-gray-700 pt-6 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Students:</span>
                    <span class="text-white font-semibold">{{ $classe->students->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Created:</span>
                    <span class="text-gray-400">{{ $classe->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-700">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded font-semibold transition">
                    Update Class
                </button>
                <a href="{{ route('admin.classes') }}" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 rounded font-semibold text-center transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
