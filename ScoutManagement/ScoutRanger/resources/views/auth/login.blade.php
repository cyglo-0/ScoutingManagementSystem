<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Pasay City Scouting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f0;
        }
        .btn-olive {
            background-color: #556B2F;
            color: #fff;
        }
        .btn-olive:hover {
            background-color: #3B4B20;
            color: #fff;
        }
        a.olive-link {
            color: #556B2F;
            text-decoration: none;
        }
        a.olive-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-sm p-4 border-0" style="width: 100%; max-width: 400px; border-radius: 10px;">
    <div class="card-body">
        <h4 class="fw-bold text-center mb-1">Pasay City Scouting System</h4>
        <p class="text-muted small text-center mb-4">Sign in to your account</p>

        @if (session('status'))
            <div class="alert alert-success small py-2">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger small py-2 mb-3">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold text-secondary">EMAIL</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-secondary">PASSWORD</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label small text-muted" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-olive w-100 py-2">Sign In</button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="olive-link">Register</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto Refresh Page if Session Expire Detected -->
<script>
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            fetch('/login', { method: 'HEAD' })
                .catch(() => window.location.reload());
        }
    });
</script>

</body>
</html>