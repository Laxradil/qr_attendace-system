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
        <!-- Class Schedules -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold text-base">All schedules</h2>
                <button class="btn-add" onclick="openModal('addScheduleModal')">+ Add schedule</button>
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
                        @foreach($schedules as $schedule)
                        <tr>
                            <td class="py-2 px-4">{{ $schedule->subject_code }}</td>
                            <td class="py-2 px-4">{{ $schedule->subject_name }}</td>
                            <td class="py-2 px-4">{{ $schedule->professor }}</td>
                            <td class="py-2 px-4">{{ $schedule->days }}</td>
                            <td class="py-2 px-4">{{ $schedule->time }}</td>
                            <td class="py-2 px-4">{{ $schedule->room }}</td>
                            <td class="py-2 px-4 flex gap-2">
                                <button class="btn-action btn-edit" onclick="editSchedule({{ $schedule->id }})">Edit</button>
                                <button class="btn-action btn-remove" onclick="removeSchedule({{ $schedule->id }}, '{{ $schedule->subject_name }}')">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <!-- Register New Account -->
        <section class="mb-12">
            <h2 class="font-semibold text-base mb-2">Register new account</h2>
            <form class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end" method="POST" action="{{ route('admin.account.create') }}">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1">Full name</label>
                    <input type="text" name="name" class="input" placeholder="e.g. Dr. Juan Dela Cruz" required>
                </div>
                <div>
                    <label class="block text-sm mb-1">Username</label>
                    <input type="text" name="username" class="input" placeholder="auto-generated or custom">
                </div>
                <div>
                    <label class="block text-sm mb-1">Role</label>
                    <select name="role" class="select" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="professor">Professor</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Temp password</label>
                    <input type="text" name="password" class="input" placeholder="Set initial password" required>
                </div>
                <div class="md:col-span-5 mt-2">
                    <button type="submit" class="btn-create">Create account</button>
                </div>
            </form>
        </section>
    </main>

    <!-- Add Account Modal -->
    <div id="addAccountModal" class="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold mb-4">Add New Account</h3>
            <form method="POST" action="{{ route('admin.account.create') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm mb-1">Full name</label>
                    <input type="text" name="name" class="input" placeholder="e.g. Dr. Juan Dela Cruz" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email" class="input" placeholder="e.g. user@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Role</label>
                    <select name="role" class="select" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="professor">Professor</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Temporary password</label>
                    <input type="text" name="password" class="input" placeholder="Set initial password" required>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" class="px-4 py-2 border border-[#e3e3e0] rounded" onclick="closeModal('addAccountModal')">Cancel</button>
                    <button type="submit" class="btn-create">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Schedule Modal -->
    <div id="addScheduleModal" class="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold mb-4">Add New Schedule</h3>
            <form method="POST" action="{{ route('schedules.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm mb-1">Subject Code</label>
                    <input type="text" name="subject_code" class="input" placeholder="e.g. CCS101" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Subject Name</label>
                    <input type="text" name="subject_name" class="input" placeholder="e.g. Intro to Computing" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Professor</label>
                    <select name="professor_id" class="select" required>
                        <option value="">Select professor</option>
                        @foreach($professors as $prof)
                        <option value="{{ $prof->id }}">{{ $prof->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Days</label>
                    <input type="text" name="days" class="input" placeholder="e.g. MWF or TTh" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Start Time</label>
                    <input type="time" name="start_time" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">End Time</label>
                    <input type="time" name="end_time" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Room</label>
                    <input type="text" name="room" class="input" placeholder="e.g. 204" required>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" class="px-4 py-2 border border-[#e3e3e0] rounded" onclick="closeModal('addScheduleModal')">Cancel</button>
                    <button type="submit" class="btn-create">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold mb-4">Edit User</h3>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm mb-1">Full name</label>
                    <input type="text" name="name" id="editUserName" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email" id="editUserEmail" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Role</label>
                    <select name="role" id="editUserRole" class="select" required>
                        <option value="admin">Admin</option>
                        <option value="professor">Professor</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" class="px-4 py-2 border border-[#e3e3e0] rounded" onclick="closeModal('editUserModal')">Cancel</button>
                    <button type="submit" class="btn-create">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div id="editScheduleModal" class="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold mb-4">Edit Schedule</h3>
            <form id="editScheduleForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editScheduleId" name="id">
                <div class="mb-3">
                    <label class="block text-sm mb-1">Subject Code</label>
                    <input type="text" name="subject_code" id="editSubjectCode" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Subject Name</label>
                    <input type="text" name="subject_name" id="editSubjectName" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Days</label>
                    <input type="text" name="days" id="editDays" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Start Time</label>
                    <input type="time" name="start_time" id="editStartTime" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">End Time</label>
                    <input type="time" name="end_time" id="editEndTime" class="input" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Room</label>
                    <input type="text" name="room" id="editRoom" class="input" required>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" class="px-4 py-2 border border-[#e3e3e0] rounded" onclick="closeModal('editScheduleModal')">Cancel</button>
                    <button type="submit" class="btn-create">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        
        function editUser(id) {
            // Fetch user data and populate edit modal
            fetch('/admin/users/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editUserName').value = data.name;
                    document.getElementById('editUserEmail').value = data.email;
                    document.getElementById('editUserRole').value = data.role;
                    document.getElementById('editUserForm').action = '/admin/users/' + id;
                    openModal('editUserModal');
                })
                .catch(error => console.error('Error:', error));
        }
        
        function resetPassword(id, name) {
            if(confirm('Reset password for ' + name + '?')) {
                fetch('/admin/users/' + id + '/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('Password reset successfully! New password: ' + data.new_password);
                })
                .catch(error => console.error('Error:', error));
            }
        }
        
        function deleteUser(id, name) {
            if(confirm('Are you sure you want to delete ' + name + '?')) {
                fetch('/admin/users/' + id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Unable to delete user.');
                    }
                    return response.json().catch(() => ({ success: true }));
                })
                .then(() => {
                    alert('User deleted successfully!');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete user. Please refresh and try again.');
                });
            }
        }
        
        function editSchedule(id) {
            // Fetch schedule data and populate edit modal
            fetch('/schedules/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editScheduleId').value = data.id;
                    document.getElementById('editSubjectCode').value = data.subject_code;
                    document.getElementById('editSubjectName').value = data.subject_name;
                    document.getElementById('editDays').value = data.days;
                    document.getElementById('editStartTime').value = data.start_time || '';
                    document.getElementById('editEndTime').value = data.end_time || '';
                    document.getElementById('editRoom').value = data.room;
                    document.getElementById('editScheduleForm').action = '/schedules/' + id;
                    openModal('editScheduleModal');
                })
                .catch(error => console.error('Error:', error));
        }
        
        function removeSchedule(id, name) {
            if(confirm('Are you sure you want to remove ' + name + '?')) {
                fetch('/schedules/' + id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('Schedule removed successfully!');
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>