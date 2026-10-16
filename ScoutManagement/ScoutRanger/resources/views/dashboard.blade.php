<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Scout System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f0; }
        .bg-olive { background-color: #556B2F; color: #fff; }
        .btn-olive { background-color: #556B2F; color: #fff; }
        .btn-olive:hover { background-color: #3B4B20; color: #fff; }
        .nav-pills .nav-link.active { background-color: #556B2F; }
        .nav-pills .nav-link { color: #556B2F; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-olive shadow-sm">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="#">Scout System</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 small">{{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                        <button class="nav-link @if($activeTab === 'overview') active @endif text-start" id="tab-overview" data-bs-toggle="pill" data-bs-target="#overview" type="button">Dashboard Overview</button>
                        <button class="nav-link @if($activeTab === 'ideals') active @endif text-start" id="tab-ideals" data-bs-toggle="pill" data-bs-target="#ideals" type="button">Scout Ideals & Oath</button>
                        <button class="nav-link @if($activeTab === 'chat') active @endif text-start" id="tab-chat" data-bs-toggle="pill" data-bs-target="#chat" type="button">System Chat</button>

                        @if ($user->role === 'admin')
                            <hr>
                            <span class="text-muted small fw-bold px-3 mb-2">ADMIN PANEL</span>
                            <button class="nav-link @if($activeTab === 'requests') active @endif text-start" id="tab-requests" data-bs-toggle="pill" data-bs-target="#requests" type="button">Leader Requests ({{ count($pendingLeaders) }})</button>
                            <button class="nav-link @if($activeTab === 'users') active @endif text-start" id="tab-users" data-bs-toggle="pill" data-bs-target="#users" type="button">User Management</button>
                        @endif

                        @if ($user->role === 'scout_leader' || $user->role === 'admin')
                            <hr>
                            <span class="text-muted small fw-bold px-3 mb-2">SCOUT DIRECTORY</span>
                            <button class="nav-link @if($activeTab === 'directory') active @endif text-start" id="tab-directory" data-bs-toggle="pill" data-bs-target="#directory" type="button">All Pasay Area Scouts</button>
                        @endif

                        @if ($user->role === 'senior_scout')
                            <hr>
                            <span class="text-muted small fw-bold px-3 mb-2">MY SCHOOL</span>
                            <button class="nav-link @if($activeTab === 'school') active @endif text-start" id="tab-school" data-bs-toggle="pill" data-bs-target="#school" type="button">School Scouts</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Tabs -->
        <div class="col-md-9">
            <div class="tab-content">

                <!-- Overview -->
                <div class="tab-pane fade @if($activeTab === 'overview') show active @endif" id="overview">
                    <div class="card border-0 shadow-sm p-4">
                        <h4 class="fw-bold">Welcome, {{ $user->name }}!</h4>
                        <p class="text-muted small mb-0">
                            Role: <strong>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</strong> 
                            | School: <strong>{{ $user->school ?? 'All Pasay High Schools' }}</strong> 
                            | Rank: <strong>{{ $user->rank ?? 'N/A' }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Scout Ideals -->
                <div class="tab-pane fade @if($activeTab === 'ideals') show active @endif" id="ideals">
                    <div class="card border-0 shadow-sm p-4">
                        <h4 class="fw-bold mb-3">Scout Ideals, Oath & Law</h4>
                        <div class="mb-3">
                            <h6 class="fw-bold text-secondary">SCOUT MOTTO</h6>
                            <p>{{ $ideals['motto'] }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-secondary">SCOUT SLOGAN</h6>
                            <p>{{ $ideals['slogan'] }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-secondary">SCOUT OATH</h6>
                            <p class="fst-italic bg-light p-3 rounded">{{ $ideals['oath'] }}</p>
                        </div>
                        <div>
                            <h6 class="fw-bold text-secondary">SCOUT LAW</h6>
                            <ol class="row">
                                @foreach ($ideals['law'] as $law)
                                    <li class="col-md-6 mb-1">{{ $law }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- System Chat Tab -->
                <div class="tab-pane fade @if($activeTab === 'chat') show active @endif" id="chat">
                    <div class="card border-0 shadow-sm p-4">
                        <h4 class="fw-bold mb-3">System Chat (General Channel)</h4>
                        
                        <!-- Live Message Window -->
                        <div id="chat-window" class="border rounded p-3 mb-3 bg-white" style="height: 350px; overflow-y: auto;">
                            <div class="text-center text-muted small py-3" id="chat-loading">Loading chat history...</div>
                        </div>

                        <!-- Chat Input Form -->
                        <form id="chat-form" class="input-group">
                            @csrf
                            <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." required autocomplete="off">
                            <button class="btn btn-olive px-4" type="submit">Send</button>
                        </form>
                    </div>
                </div>

                @if ($user->role === 'admin')
                    <!-- Pending Requests -->
                    <div class="tab-pane fade @if($activeTab === 'requests') show active @endif" id="requests">
                        <div class="card border-0 shadow-sm p-4">
                            <h4 class="fw-bold mb-3">Pending Scout Leader Requests</h4>

                            <!-- Flash Banners -->
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show py-2 px-3 small mb-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('info'))
                                <div class="alert alert-info alert-dismissible fade show py-2 px-3 small mb-3" role="alert">
                                    {{ session('info') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (count($pendingLeaders) > 0)
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>School</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingLeaders as $leader)
                                            <tr>
                                                <td>{{ $leader->name }}</td>
                                                <td>{{ $leader->school }}</td>
                                                <td>{{ $leader->email }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <form method="POST" action="{{ route('admin.leader.approve', $leader->id) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                        </form>

                                                        <form method="POST" action="{{ route('admin.leader.reject', $leader->id) }}" onsubmit="return confirm('Are you sure you want to reject this request?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted small mb-0">No pending Scout Leader requests.</p>
                            @endif
                        </div>
                    </div>

                    <!-- User Management Tab -->
                    <div class="tab-pane fade @if($activeTab === 'users') show active @endif" id="users">
                        <div class="card border-0 shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold mb-0">User Management</h4>
                                <!-- Add User Trigger -->
                                <button class="btn btn-olive btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add New User</button>
                            </div>

                            @if (session('success') && $activeTab === 'users')
                                <div class="alert alert-success alert-dismissible fade show py-2 px-3 small mb-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Search and School Filter Bar -->
                            <div class="card bg-light border-0 mb-3 p-3">
                                <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-center">
                                    <input type="hidden" name="tab" value="users">
                                    <div class="col-md-5">
                                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email..." value="{{ $searchKeyword }}">
                                    </div>
                                    <div class="col-md-5">
                                        <select name="school" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="">-- All High Schools in Pasay --</option>
                                            @foreach ($schoolsList as $school)
                                                <option value="{{ $school }}" @if($selectedSchool == $school) selected @endif>
                                                    {{ $school }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex gap-1">
                                        <button type="submit" class="btn btn-sm btn-olive w-100">Search</button>
                                        @if ($selectedSchool || $searchKeyword)
                                            <a href="{{ route('dashboard', ['tab' => 'users']) }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>School</th>
                                        <th>Rank</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allUsers as $u)
                                        <tr>
                                            <td class="fw-semibold">{{ $u->name }}</td>
                                            <td><span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $u->role)) }}</span></td>
                                            <td>{{ $u->school ?? 'N/A' }}</td>
                                            <td>{{ $u->rank ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <!-- Edit Button Modal Trigger -->
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}">Edit</button>

                                                    <!-- Delete Form -->
                                                    <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" onsubmit="return confirm('Are you sure you want to delete {{ $u->name }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- EDIT USER MODAL -->
                                        <div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit User: {{ $u->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.users.update', $u->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body text-start">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold small">Full Name</label>
                                                                <input type="text" name="name" class="form-control form-control-sm" value="{{ $u->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold small">Email Address</label>
                                                                <input type="email" name="email" class="form-control form-control-sm" value="{{ $u->email }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold small">Phone Number</label>
                                                                <input type="text" name="phone" class="form-control form-control-sm" value="{{ $u->phone }}" maxlength="11" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold small">School</label>
                                                                <select name="school" class="form-select form-select-sm" required>
                                                                    @foreach ($schoolsList as $sch)
                                                                        <option value="{{ $sch }}" {{ $u->school == $sch ? 'selected' : '' }}>{{ $sch }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold small">Role</label>
                                                                <select name="role" class="form-select form-select-sm" required>
                                                                    <option value="senior_scout" {{ $u->role == 'senior_scout' ? 'selected' : '' }}>Senior Scout</option>
                                                                    <option value="scout_leader" {{ $u->role == 'scout_leader' ? 'selected' : '' }}>Scout Leader</option>
                                                                    <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>System Admin</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold small">Password (leave blank to keep current)</label>
                                                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Optional">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-olive btn-sm">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">No members found matching your search.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($user->role === 'scout_leader' || $user->role === 'admin')
                    <!-- All Area Scouts -->
                    <div class="tab-pane fade @if($activeTab === 'directory') show active @endif" id="directory">
                        <div class="card border-0 shadow-sm p-4">
                            <h4 class="fw-bold mb-3">Scouts Across Pasay Schools</h4>

                            <!-- Search and School Filter Bar -->
                            <div class="card bg-light border-0 mb-3 p-3">
                                <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-center">
                                    <input type="hidden" name="tab" value="directory">
                                    <div class="col-md-5">
                                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email..." value="{{ $searchKeyword }}">
                                    </div>
                                    <div class="col-md-5">
                                        <select name="school" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="">-- All High Schools in Pasay --</option>
                                            @foreach ($schoolsList as $school)
                                                <option value="{{ $school }}" @if($selectedSchool == $school) selected @endif>
                                                    {{ $school }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex gap-1">
                                        <button type="submit" class="btn btn-sm btn-olive w-100">Search</button>
                                        @if ($selectedSchool || $searchKeyword)
                                            <a href="{{ route('dashboard', ['tab' => 'directory']) }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>School</th>
                                        <th>Role</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allUsers as $u)
                                        <tr>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->school ?? 'N/A' }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $u->role)) }}</td>
                                            <td>{{ $u->rank ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No scouts found matching your search.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($user->role === 'senior_scout')
                    <!-- School-Specific Scouts -->
                    <div class="tab-pane fade @if($activeTab === 'school') show active @endif" id="school">
                        <div class="card border-0 shadow-sm p-4">
                            <h4 class="fw-bold mb-3">Senior Scouts at {{ $user->school }}</h4>

                            <!-- Search Bar for Senior Scout -->
                            <div class="card bg-light border-0 mb-3 p-3">
                                <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-center">
                                    <input type="hidden" name="tab" value="school">
                                    <div class="col-md-9">
                                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email..." value="{{ $searchKeyword }}">
                                    </div>
                                    <div class="col-md-3 d-flex gap-1">
                                        <button type="submit" class="btn btn-sm btn-olive w-100">Search</button>
                                        @if ($searchKeyword)
                                            <a href="{{ route('dashboard', ['tab' => 'school']) }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Rank</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($schoolScouts as $scout)
                                        <tr>
                                            <td>{{ $scout->name }}</td>
                                            <td>{{ $scout->rank ?? 'N/A' }}</td>
                                            <td>{{ $scout->email }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No scouts found matching your search.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- ADD USER MODAL -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Birthdate</label>
                            <input type="date" id="add_birthday" name="birthday" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Age</label>
                            <input type="number" id="add_age" name="age" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Phone Number</label>
                        <input type="text" id="add_phone" name="phone" class="form-control form-control-sm" placeholder="09123456789" maxlength="11" required autocomplete="off">
                        <div id="modal-phone-feedback" class="form-text mt-1" style="font-size: 0.78rem;">
                            Format: Starts with 09 (11 digits).
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pasay High School</label>
                        <select name="school" class="form-select form-select-sm" required>
                            <option value="">-- Select School --</option>
                            @foreach ($schoolsList as $sch)
                                <option value="{{ $sch }}">{{ $sch }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role</label>
                        <select name="role" class="form-select form-select-sm" required>
                            <option value="senior_scout">Senior Scout</option>
                            <option value="scout_leader">Scout Leader</option>
                            <option value="admin">System Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_submit_btn" class="btn btn-olive btn-sm">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto Calculate Age & Real-Time Phone Validation in Modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Auto Calculate Age
    const bday = document.getElementById('add_birthday');
    const age = document.getElementById('add_age');
    if (bday && age) {
        bday.addEventListener('change', function () {
            const birthDate = new Date(this.value);
            const today = new Date();
            if (isNaN(birthDate.getTime())) return;
            let calculatedAge = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                calculatedAge--;
            }
            age.value = calculatedAge > 0 ? calculatedAge : 0;
        });
    }

    // 2. Optimized Real-Time Phone Check (Debounced)
    const phoneInput = document.getElementById('add_phone');
    const phoneFeedback = document.getElementById('modal-phone-feedback');
    const submitBtn = document.getElementById('add_submit_btn');
    let debounceTimer;

    if (phoneInput && phoneFeedback) {
        phoneInput.addEventListener('input', function () {
            clearTimeout(debounceTimer); // Clear pending network request

            const phoneVal = this.value.trim();
            const phoneRegex = /^09\d{9}$/;

            if (phoneVal.length === 0) {
                phoneFeedback.textContent = "Format: Starts with 09 (11 digits).";
                phoneFeedback.className = "form-text mt-1 text-muted";
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (!phoneRegex.test(phoneVal)) {
                phoneFeedback.textContent = "Format: Must start with 09 and be 11 digits.";
                phoneFeedback.className = "form-text mt-1 text-danger fw-semibold";
                if (submitBtn) submitBtn.disabled = true;
                return;
            }

            // Show immediate loading indicator
            phoneFeedback.textContent = "Checking availability...";
            phoneFeedback.className = "form-text mt-1 text-info fw-semibold";

            // Debounce: Wait 300ms after user stops typing before making HTTP request
            debounceTimer = setTimeout(() => {
                fetch("{{ route('phone.check') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ 
                        phone: phoneVal,
                        phone_number: phoneVal 
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server returned error status');
                    return response.json();
                })
                .then(data => {
                    if (data.exists) {
                        phoneFeedback.textContent = "This phone number is already registered.";
                        phoneFeedback.className = "form-text mt-1 text-danger fw-semibold";
                        if (submitBtn) submitBtn.disabled = true;
                    } else {
                        phoneFeedback.textContent = "Phone number is available!";
                        phoneFeedback.className = "form-text mt-1 text-success fw-semibold";
                        if (submitBtn) submitBtn.disabled = false;
                    }
                })
                .catch(() => {
                    phoneFeedback.textContent = "Error checking phone number availability.";
                    phoneFeedback.className = "form-text mt-1 text-warning";
                    if (submitBtn) submitBtn.disabled = false;
                });
            }, 300);
        });
    }
});
</script>

<!-- Chat Config & External Script -->
<script>
    window.currentUserId = {{ Auth::id() }};
    window.chatFetchUrl = "{{ route('chat.fetch') }}";
    window.chatSendUrl = "{{ route('chat.send') }}";
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/chat.js') }}"></script>

</body>
</html>