@extends('layouts.app')

@section('title', 'Settings - Professor')
@section('header', 'My Settings')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Profile Settings</h2>

        <form action="{{ route('professor.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Email Address</label>
                <input type="email" name="email" value="{{ $user->email }}" required 
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username (read-only) -->
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-2">Username</label>
                <input type="text" value="{{ $user->username }}" disabled 
                    class="w-full bg-gray-800 border border-gray-700 text-gray-500 rounded px-4 py-2 cursor-not-allowed">
                <p class="text-gray-500 text-xs mt-1">Username cannot be changed</p>
            </div>

            <!-- Change Password Section -->
            <div class="border-t border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-white mb-4">Change Password</h3>

                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm font-semibold mb-2">New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current password" 
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-gray-300 text-sm font-semibold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm new password" 
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:border-purple-500 outline-none transition">
                </div>
            </div>

            <!-- Account Info (read-only) -->
            <div class="border-t border-gray-700 pt-6 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Role:</span>
                    <span class="text-white font-semibold capitalize">{{ $user->role }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Status:</span>
                    <span class="inline-block px-3 py-1 {{ $user->is_active ? 'bg-green-900/30 text-green-300' : 'bg-red-900/30 text-red-300' }} text-xs rounded-full font-semibold">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Member Since:</span>
                    <span class="text-white font-semibold">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Submit -->
            <div class="border-t border-gray-700 pt-6 flex gap-4">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded font-semibold transition">
                    Save Changes
                </button>
                <a href="{{ route('professor.dashboard') }}" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 rounded font-semibold text-center transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
