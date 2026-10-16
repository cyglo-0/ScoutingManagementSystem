<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Scout System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f0; }
        .bg-olive { background-color: #556B2F; color: #fff; }
        .btn-olive { background-color: #556B2F; color: #fff; }
        .btn-olive:hover { background-color: #3B4B20; color: #fff; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-4">

<div class="container" style="max-width: 520px;">
    <div class="card border-0 shadow-sm p-4">
        <h3 class="fw-bold text-center mb-1">Scout System</h3>
        <p class="text-muted text-center small mb-4">Create your account</p>

        <!-- General Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold small">Full Name</label>
                <input type="text" id="name" name="name" class="form-control form-control-sm" value="{{ old('name') }}" required autofocus>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold small">Email Address</label>
                <input type="email" id="email" name="email" class="form-control form-control-sm" value="{{ old('email') }}" required>
            </div>

            <!-- Birthdate, Age & Phone Number Row -->
            <div class="row g-2 mb-3">
                <div class="col-md-5">
                    <label for="birthday" class="form-label fw-semibold small">Birthdate</label>
                    <input type="date" 
                           id="birthday" 
                           name="birthday" 
                           class="form-control form-control-sm" 
                           value="{{ old('birthday') }}" 
                           required>
                </div>
                <div class="col-md-3">
                    <label for="age" class="form-label fw-semibold small">Age</label>
                    <input type="number" 
                           id="age" 
                           name="age" 
                           class="form-control form-control-sm" 
                           placeholder="Auto" 
                           min="10" 
                           max="80" 
                           value="{{ old('age') }}" 
                           readonly 
                           required>
                </div>
                <div class="col-md-4">
                    <label for="phone" class="form-label fw-semibold small">Phone Number</label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           class="form-control form-control-sm" 
                           placeholder="09123456789" 
                           maxlength="11" 
                           value="{{ old('phone') }}" 
                           required>
                    <div id="phone-feedback" class="form-text mt-1" style="font-size: 0.78rem;">
                        Format: Starts with 09 (11 digits).
                    </div>
                </div>
            </div>

            <!-- School Selection -->
            <div class="mb-3">
                <label for="school" class="form-label fw-semibold small">Pasay High School</label>
                <select id="school" name="school" class="form-select form-select-sm" required>
                    <option value="">-- Select Your School --</option>
                    <option value="Kalayaan High School" {{ old('school') == 'Kalayaan High School' ? 'selected' : '' }}>Kalayaan High School</option>
                    <option value="Pasay City South High School" {{ old('school') == 'Pasay City South High School' ? 'selected' : '' }}>Pasay City South High School</option>
                    <option value="Pasay City National High School" {{ old('school') == 'Pasay City National High School' ? 'selected' : '' }}>Pasay City National High School</option>
                    <option value="Pasay City West High School" {{ old('school') == 'Pasay City West High School' ? 'selected' : '' }}>Pasay City West High School</option>
                    <option value="Pasay City National Science High School" {{ old('school') == 'Pasay City National Science High School' ? 'selected' : '' }}>Pasay City National Science High School</option>
                    <option value="President Corazon “Cory” C. Aquino National High School" {{ old('school') == 'President Corazon “Cory” C. Aquino National High School' ? 'selected' : '' }}>President Corazon “Cory” C. Aquino National High School</option>
                    <option value="Pasay City North High School – M. Dela Cruz Campus" {{ old('school') == 'Pasay City North High School – M. Dela Cruz Campus' ? 'selected' : '' }}>Pasay City North High School – M. Dela Cruz Campus</option>
                    <option value="Pasay City East High School" {{ old('school') == 'Pasay City East High School' ? 'selected' : '' }}>Pasay City East High School</option>
                </select>
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label for="role" class="form-label fw-semibold small">Role</label>
                <select id="role" name="role" class="form-select form-select-sm" required>
                    <option value="senior_scout" {{ old('role') == 'senior_scout' ? 'selected' : '' }}>Senior Scout</option>
                    <option value="scout_leader" {{ old('role') == 'scout_leader' ? 'selected' : '' }}>Scout Leader (Requires Admin Approval)</option>
                </select>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold small">Password</label>
                <input type="password" id="password" name="password" class="form-control form-control-sm" required>
                <div id="password-feedback" class="form-text mt-1" style="font-size: 0.8rem;">
                    Minimum 8 characters required.
                </div>
            </div>

            <!-- Password Confirmation Field -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-semibold small">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-sm" required>
                <div id="confirm-feedback" class="form-text mt-1" style="font-size: 0.8rem;">
                    Passwords must match.
                </div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-olive w-100 py-2">Register Account</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-decoration-none text-secondary">Already have an account? Sign In</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto-calculate Age Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const birthdayInput = document.getElementById('birthday');
    const ageInput = document.getElementById('age');

    if (birthdayInput && ageInput) {
        birthdayInput.addEventListener('change', function () {
            const birthDate = new Date(this.value);
            const today = new Date();
            
            if (isNaN(birthDate.getTime())) return;

            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            ageInput.value = age > 0 ? age : 0;
        });
    }
});
</script>

<!-- Pass Global Config -->
<script>
    window.phoneCheckUrl = "{{ route('phone.check') }}";
    window.csrfToken = "{{ csrf_token() }}";
</script>

<!-- Dedicated Real-time Scripts -->
<script src="{{ asset('js/register-phone.js') }}" defer></script>
<script src="{{ asset('js/register-password.js') }}" defer></script>

</body>
</html>