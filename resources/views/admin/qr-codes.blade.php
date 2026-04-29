@extends('layouts.app')

@section('title', 'QR Codes Management - Admin')
@section('header', 'QR Codes Management')

@section('content')
<div class="p-6 space-y-6">
    <!-- Generate QR Codes -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4">Generate QR Codes</h2>
        <form action="{{ route('admin.qr-codes.generate') }}" method="POST" class="flex gap-4 flex-wrap">
            @csrf
            
            <div class="flex-1 min-w-48">
                <select name="class_id" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-3 py-2 focus:border-purple-500 outline-none">
                    <option value="">Select a class...</option>
                    @foreach($classes ?? [] as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->code }} - {{ $classe->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-32">
                <input type="number" name="count" value="1" min="1" max="100" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-3 py-2 focus:border-purple-500 outline-none" placeholder="Quantity">
            </div>

            <div class="flex-1 min-w-48">
                <input type="date" name="expires_at" class="w-full bg-gray-800 border border-gray-700 text-white rounded px-3 py-2 focus:border-purple-500 outline-none" placeholder="Expiration date (optional)">
            </div>

            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded font-semibold transition">
                Generate
            </button>
        </form>
    </div>

    <!-- QR Codes Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">UUID</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Class</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Professor</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Status</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Used At</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Expires At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($qrCodes as $qr)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ substr($qr->uuid, 0, 12) }}...</td>
                            <td class="px-6 py-4 text-white">{{ $qr->classe->code }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $qr->professor->name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 {{ $qr->is_used ? 'bg-green-900/30 text-green-300' : 'bg-amber-900/30 text-amber-300' }} text-xs rounded-full font-semibold">
                                    {{ $qr->is_used ? 'Used' : 'Unused' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $qr->used_at?->format('M d, Y H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $qr->expires_at?->format('M d, Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">No QR codes generated yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($qrCodes->hasPages())
        <div class="flex justify-center">
            {{ $qrCodes->links() }}
        </div>
    @endif
</div>
@endsection
