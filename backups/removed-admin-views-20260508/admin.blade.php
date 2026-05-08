<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance - Admin Dashboard</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        .role-admin { color: #f53003; background: #fff2f2; border-radius: 6px; padding: 2px 10px; font-size: 13px; font-weight: 500; }
        .role-prof { color: #2563eb; background: #e0e7ff; border-radius: 6px; padding: 2px 10px; font-size: 13px; font-weight: 500; }
        .role-student { color: #059669; background: #d1fae5; border-radius: 6px; padding: 2px 10px; font-size: 13px; font-weight: 500; }
        .btn-action { font-size: 13px; border-radius: 6px; padding: 2px 12px; font-weight: 500; cursor: pointer; }
        .btn-edit { color: #1b1b18; background: #dbdbd7; }
        .btn-reset { color: #f53003; background: #fff2f2; border: 1px solid #f53003; }
        .btn-delete, .btn-remove { color: #f53003; background: #fff2f2; border: 1px solid #f53003; }
        .btn-add, .btn-create { background: #1b1b18; color: #fff; border-radius: 6px; font-size: 14px; font-weight: 500; padding: 6px 18px; cursor: pointer; border: none; }
        .input, .select { border: 1px solid #e3e3e0; border-radius: 6px; padding: 8px 12px; font-size: 14px; width: 100%; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%; }
    </style>
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] min-h-screen">
    <header class="flex items-center justify-between py-6 px-10 border-b border-[#e3e3e0]">
        <div class="text-xl font-semibold tracking-tight">QR Attendance</div>
        <nav class="flex items-center gap-2">
            <span class="role-admin mr-2">Admin</span>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 rounded-sm bg-black text-white font-medium">Dashboard</a>
            <a href="{{ route('admin.accounts') }}" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Accounts</a>
            <a href="{{ route('admin.schedules') }}" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Schedules</a>
            <a href="{{ route('admin.reports') }}" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Reports</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Logout</button>
            </form>
        </nav>
    </header>
    <main class="max-w-6xl mx-auto mt-8">
        <!-- Overview Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">{{ $professorsCount }}</div>
                <div class="text-[#706f6c] mt-2">Professors</div>
            </div>
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">{{ $studentsCount }}</div>
                <div class="text-[#706f6c] mt-2">Students</div>
            </div>
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">{{ $schedulesCount }}</div>
                <div class="text-[#706f6c] mt-2">Schedules</div>
            </div>
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">{{ $classesToday }}</div>
                <div class="text-[#706f6c] mt-2">Classes today</div>
            </div>
        </section>
        <!-- User Accounts -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold text-base">All accounts</h2>
                <button class="btn-add" onclick="openModal('addAccountModal')">+ Add account</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-[#e3e3e0] rounded-lg">
                    <thead class="bg-[#FDFDFC]">
                        <tr>
                            <th class="py-2 px-4 text-left">FULL NAME</th>
                            <th class="py-2 px-4 text-left">USERNAME</th>
                            <th class="py-2 px-4 text-left">ROLE</th>
                            <th class="py-2 px-4 text-left">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="py-2 px-4">{{ $user->name }}</td>
                            <td class="py-2 px-4">{{ $user->username }}</td>
                            <td class="py-2 px-4">
                                <span class="role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit" onclick="editUser({{ $user->id }})">Edit</button>
                                <button class="btn-action btn-reset" onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')">Reset password</button>
                                <button class="btn-action btn-delete" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>