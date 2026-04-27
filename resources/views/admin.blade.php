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
        .btn-action { font-size: 13px; border-radius: 6px; padding: 2px 12px; font-weight: 500; }
        .btn-edit { color: #1b1b18; background: #dbdbd7; }
        .btn-reset { color: #f53003; background: #fff2f2; border: 1px solid #f53003; }
        .btn-delete, .btn-remove { color: #f53003; background: #fff2f2; border: 1px solid #f53003; }
        .btn-add, .btn-create { background: #1b1b18; color: #fff; border-radius: 6px; font-size: 14px; font-weight: 500; padding: 6px 18px; }
        .input, .select { border: 1px solid #e3e3e0; border-radius: 6px; padding: 8px 12px; font-size: 14px; width: 100%; }
    </style>
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] min-h-screen">
    <header class="flex items-center justify-between py-6 px-10 border-b border-[#e3e3e0]">
        <div class="text-xl font-semibold tracking-tight">QR Attendance</div>
        <nav class="flex items-center gap-2">
            <span class="role-admin mr-2">Admin</span>
            <a href="#" class="px-4 py-1.5 rounded-sm bg-black text-white font-medium">Dashboard</a>
            <a href="#" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Accounts</a>
            <a href="#" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Schedules</a>
            <a href="#" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Reports</a>
            <a href="#" class="px-4 py-1.5 rounded-sm border border-[#e3e3e0]">Logout</a>
        </nav>
    </header>
    <main class="max-w-6xl mx-auto mt-8">
        <!-- Overview Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">12</div>
                <div class="text-[#706f6c] mt-2">Professors</div>
            </div>
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">340</div>
                <div class="text-[#706f6c] mt-2">Students</div>
            </div>
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">28</div>
                <div class="text-[#706f6c] mt-2">Schedules</div>
            </div>
            <div class="bg-[#FDFDFC] border border-[#e3e3e0] rounded-lg p-6 text-center">
                <div class="text-3xl font-bold">4</div>
                <div class="text-[#706f6c] mt-2">Classes today</div>
            </div>
        </section>
        <!-- User Accounts -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold text-base">All accounts</h2>
                <button class="btn-add">+ Add account</button>
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
                        <tr>
                            <td class="py-2 px-4">Dr. Ana Santos</td>
                            <td class="py-2 px-4">prof_santos</td>
                            <td class="py-2 px-4"><span class="role-prof">Professor</span></td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit">Edit</button>
                                <button class="btn-action btn-reset">Reset password</button>
                                <button class="btn-action btn-delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4">Carlos Reyes</td>
                            <td class="py-2 px-4">prof_reyes</td>
                            <td class="py-2 px-4"><span class="role-prof">Professor</span></td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit">Edit</button>
                                <button class="btn-action btn-reset">Reset password</button>
                                <button class="btn-action btn-delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4">Maria Cruz</td>
                            <td class="py-2 px-4">stud_cruz</td>
                            <td class="py-2 px-4"><span class="role-student">Student</span></td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit">Edit</button>
                                <button class="btn-action btn-delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4">Admin User</td>
                            <td class="py-2 px-4">admin01</td>
                            <td class="py-2 px-4"><span class="role-admin">Admin</span></td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- Class Schedules -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold text-base">All schedules</h2>
                <button class="btn-add">+ Add schedule</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-[#e3e3e0] rounded-lg">
                    <thead class="bg-[#FDFDFC]">
                        <tr>
                            <th class="py-2 px-4 text-left">SUBJECT CODE</th>
                            <th class="py-2 px-4 text-left">SUBJECT NAME</th>
                            <th class="py-2 px-4 text-left">PROFESSOR</th>
                            <th class="py-2 px-4 text-left">DAYS</th>
                            <th class="py-2 px-4 text-left">TIME</th>
                            <th class="py-2 px-4 text-left">ROOM</th>
                            <th class="py-2 px-4 text-left">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4">CCS101</td>
                            <td class="py-2 px-4">Intro to Computing</td>
                            <td class="py-2 px-4">Dr. Santos</td>
                            <td class="py-2 px-4">MWF</td>
                            <td class="py-2 px-4">7:30–9:00</td>
                            <td class="py-2 px-4">204</td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit">Edit</button>
                                <button class="btn-action btn-remove">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4">CCS202</td>
                            <td class="py-2 px-4">Data Structures</td>
                            <td class="py-2 px-4">Prof. Reyes</td>
                            <td class="py-2 px-4">TTh</td>
                            <td class="py-2 px-4">10:00–11:30</td>
                            <td class="py-2 px-4">301</td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit">Edit</button>
                                <button class="btn-action btn-remove">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- Register New Account -->
        <section class="mb-12">
            <h2 class="font-semibold text-base mb-2">Register new account</h2>
            <form class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1">Full name</label>
                    <input type="text" class="input" placeholder="e.g. Dr. Juan Dela Cruz">
                </div>
                <div>
                    <label class="block text-sm mb-1">Username</label>
                    <input type="text" class="input" placeholder="auto-generated or custom">
                </div>
                <div>
                    <label class="block text-sm mb-1">Role</label>
                    <input type="text" class="input" placeholder="Admin / Professor / Student">
                </div>
                <div>
                    <label class="block text-sm mb-1">Temp password</label>
                    <input type="text" class="input" placeholder="Set initial password">
                </div>
                <div class="md:col-span-5 mt-2">
                    <button type="button" class="btn-create">Create account</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
