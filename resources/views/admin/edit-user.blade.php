@extends('layouts.app')

@section('title', 'Edit User - Admin')
@section('header', 'Edit User: ' . $user->name)

@section('content')
<div class="p-6 max-w-2xl">
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ $user->name }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" value="{{ $user->email }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Username *</label>
                <input type="text" name="username" value="{{ $user->username }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('username')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Role *</label>
                <select name="role" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="professor" {{ $user->role == 'professor' ? 'selected' : '' }}>Professor</option>
                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                </select>
                @error('role')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Student ID -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Student ID (if applicable)</label>
                <input type="text" name="student_id" value="{{ $user->student_id }}" 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('student_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Status</label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" {{ $user->is_active ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-gray-300">Active</span>
                </label>
            </div>

            <!-- Password Change -->
            <div class="border-t border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-white mb-4">Change Password (leave blank to keep current)</h3>
                
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm font-semibold mb-2">New Password</label>
                    <input type="password" name="password" 
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-300 text-sm font-semibold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" 
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-700">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded font-semibold transition">
                    Update User
                </button>
                <a href="{{ route('admin.users') }}" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 rounded font-semibold text-center transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
