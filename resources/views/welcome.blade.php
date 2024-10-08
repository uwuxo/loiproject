<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security card project</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (Tùy chọn) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Tùy chọn: Thêm các CSS tùy chỉnh nếu cần -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .button-container {
            text-align: center;
        }
        .btn-custom {
            width: 150px;
            margin: 10px;
        }
        .btn-custom:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
        }
    </style>
</head>
<body>

    <div class="button-container">
        <h1 class="mb-4">Welcome to security card project</h1>
        @guest
        <a href="{{ url('/login') }}" class="btn btn-success btn-custom">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
            @else
            {{ Auth::user()->name }}
            <a href="{{ route('show.user') }}" class="btn btn-success btn-custom"><i class="bi bi-person"></i> My Profile</a>
            @if (Auth::user()->super)
                <a href="{{ url('/admin/register') }}" class="btn btn-success btn-custom"><i class="bi bi-person-plus"></i> Create User</a>
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-custom"><i class="bi bi-tools"></i> Dashboard</a>
            @endif
            <a class="" href="{{ route('logout2') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>


            <form id="logout-form" action="{{ route('logout2') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endguest
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
