<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Information</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <!-- Phần Thông Tin Học Viên -->
    <div class="card mb-4">
        <div class="card-header">
            <h2>Member Information</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Ảnh đại diện học viên -->
                <div class="col-md-3 text-center">
                    <img src="https://via.placeholder.com/150" class="img-fluid rounded-circle" alt="Avatar Học Viên">
                </div>
                <!-- Thông tin cá nhân -->
                <div class="col-md-9">
                    <h4>{{ $user->name }}</h4>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần Danh Sách Khóa Học -->
    <div class="card">
        <div class="card-header">
            <h2>Participating Groups</h2>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Groups</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($user->courses))
                    @foreach ($user->courses as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>