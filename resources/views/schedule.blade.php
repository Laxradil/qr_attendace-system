<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management - Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3a5f;
            --secondary-color: #3d7ab5;
            --accent-color: #5fa8e3;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            min-height: 100vh;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #0d1b2a 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 0;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        
        .sidebar .nav-link i {
            width: 25px;
        }
        
        .main-content {
            padding: 30px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        }
        
        .table thead th {
            background: var(--light-bg);
            border-bottom: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .table tbody tr:hover {
            background: rgba(30, 58, 95, 0.05);
        }
        
        .badge-role {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-admin { background: #dc3545; color: white; }
        .badge-professor { background: #0d6efd; color: white; }
        .badge-student { background: #198754; color: white; }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .btn-edit { background: #0d6efd; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-reset { background: #6c757d; color: white; }
        
        .search-box {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
        }
        
        .search-box:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(61, 122, 181, 0.1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 0;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(61, 122, 181, 0.1);
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .icon-blue { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .icon-green { background: rgba(25, 135, 84, 0.1); color: #198754; }
        .icon-purple { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }
        .icon-orange { background: rgba(253, 126, 20, 0.1); color: #fd7e14; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white mb-0"><i class="fas fa-graduation-cap me-2"></i>Attendance</h4>
                        <small class="text-white-50">Admin Panel</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="/admin" class="nav-link">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/accounts" class="nav-link">
                                <i class="fas fa-users me-2"></i>Accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/schedules" class="nav-link active">
                                <i class="fas fa-calendar me-2"></i>Schedules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/reports" class="nav-link">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                        <li class="nav-item mt-5">
                            <a href="/logout" class="nav-link text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <div class="main-content">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="mb-1">Schedule Management</h2>
                            <p class="text-muted mb-0">Manage class schedules and timetables</p>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                            <i class="fas fa-plus me-2"></i>Add Schedule
                        </button>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-0">Total Schedules</p>
                                        <h3 class="mb-0">{{ count($schedules) }}</h3>
                                    </div>
                                    <div class="stats-icon icon-blue">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-0">Professors</p>
                                        <h3 class="mb-0">{{ $professors->count() }}</h3>
                                    </div>
                                    <div class="stats-icon icon-green">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-0">Subjects</p>
                                        <h3 class="mb-0">{{ count($schedules) }}</h3>
                                    </div>
                                    <div class="stats-icon icon-purple">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-0">Rooms</p>
                                        <h3 class="mb-0">{{ $schedules->pluck('room')->unique()->count() }}</h3>
                                    </div>
                                    <div class="stats-icon icon-orange">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Schedule Table -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Schedules</h5>
                            <div class="d-flex">
                                <input type="text" class="form-control search-box" placeholder="Search schedules..." id="searchInput">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="scheduleTable">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Professor</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($schedules as $schedule)
                                        <tr>
                                            <td><strong>{{ $schedule->subject_code }}</strong></td>
                                            <td>{{ $schedule->subject_name }}</td>
                                            <td>
                                                <span class="badge-role badge-professor">
                                                    <i class="fas fa-user me-1"></i>{{ $schedule->professor }}
                                                </span>
                                            </td>
                                            <td>{{ $schedule->days }}</td>
                                            <td>{{ $schedule->time }}</td>
                                            <td><i class="fas fa-door me-1"></i>{{ $schedule->room }}</td>
                                            <td>
                                                <button class="action-btn btn-edit" title="Edit" onclick="editSchedule({{ $schedule->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="action-btn btn-delete" title="Delete" onclick="deleteSchedule({{ $schedule->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">No schedules found. Add your first schedule!</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Schedule</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.schedule.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Subject Code</label>
                            <input type="text" class="form-control" name="subject_code" placeholder="e.g., CS101" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Name</label>
                            <input type="text" class="form-control" name="subject_name" placeholder="e.g., Introduction to Computer Science" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Professor</label>
                            <select class="form-select" name="professor_id" required>
                                <option value="">Select Professor</option>
                                @foreach($professors as $professor)
                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Days</label>
                            <input type="text" class="form-control" name="days" placeholder="e.g., Mon, Wed, Fri" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time</label>
                            <input type="text" class="form-control" name="time" placeholder="e.g., 9:00 AM - 10:30 AM" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room</label>
                            <input type="text" class="form-control" name="room" placeholder="e.g., Room 101" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Schedule</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editScheduleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editScheduleId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="editSubjectCode" name="subject_code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="editSubjectName" name="subject_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Days</label>
                            <input type="text" class="form-control" id="editDays" name="days" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time</label>
                            <input type="text" class="form-control" id="editTime" name="time" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room</label>
                            <input type="text" class="form-control" id="editRoom" name="room" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this schedule? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('scheduleTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        
        // Edit schedule function
        function editSchedule(id) {
            // Fetch schedule data and populate edit modal
            fetch(`/admin/schedules/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editScheduleId').value = data.id;
                    document.getElementById('editSubjectCode').value = data.subject_code;
                    document.getElementById('editSubjectName').value = data.subject_name;
                    document.getElementById('editDays').value = data.days;
                    document.getElementById('editTime').value = data.time;
                    document.getElementById('editRoom').value = data.room;
                    
                    document.getElementById('editScheduleForm').action = `/admin/schedules/${id}`;
                    new bootstrap.Modal(document.getElementById('editScheduleModal')).show();
                });
        }
        
        // Delete schedule function
        function deleteSchedule(id) {
            document.getElementById('deleteForm').action = `/admin/schedules/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>