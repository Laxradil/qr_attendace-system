@extends('layouts.app')

@section('title', 'Create User - Admin')
@section('header', 'Create New User')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Username *</label>
                <input type="text" name="username" value="{{ old('username') }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('username')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Role *</label>
                <select name="role" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                    <option value="">Select a role...</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="professor" {{ old('role') == 'professor' ? 'selected' : '' }}>Professor</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
                @error('role')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Student ID (for students only) -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Student ID (if applicable)</label>
                <input type="text" name="student_id" value="{{ old('student_id') }}" 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('student_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Password *</label>
                <input type="password" name="password" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Confirm Password *</label>
                <input type="password" name="password_confirmation" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('password_confirmation')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-700">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded font-semibold transition">
                    Create User
                </button>
                <a href="{{ route('admin.users') }}" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 rounded font-semibold text-center transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
