<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - PapaPriza Admin</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('Images/flamingo_pizza.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 35px 20px;
            background: #fff5f8;
            color: #6b3f4c;
            font-family: 'Poppins', sans-serif;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .admin-header,
        .filter-card,
        .table-wrapper {
            background: white;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(107, 63, 76, 0.08);
            margin-bottom: 24px;
        }

        .admin-header {
            padding: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
        }

        h1 {
            color: #a83f68;
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            margin: 12px 0 0;
        }

        .back-link {
            color: #c44776;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #923153;
        }

        .filter-card {
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .filter-card form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px 14px;
            border: 1px solid #e5bdcc;
            border-radius: 10px;
            background: white;
            color: #6b3f4c;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            min-width: 240px;
        }

        button,
        .btn-link {
            padding: 10px 18px;
            border: none;
            border-radius: 25px;
            background: #d85b87;
            color: white;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        button:hover,
        .btn-link:hover {
            background: #b9436d;
        }

        .btn-secondary {
            background: #fce7ef;
            color: #923153;
        }

        .btn-secondary:hover {
            background: #f4d0de;
        }

        .btn-danger {
            background: #fde8e8;
            color: #b42318;
            padding: 8px 14px;
            font-size: 13px;
        }

        .btn-danger:hover {
            background: #b42318;
            color: white;
        }

        .btn-edit {
            background: #eef2ff;
            color: #3730a3;
            padding: 8px 14px;
            font-size: 13px;
            margin-right: 6px;
        }

        .btn-edit:hover {
            background: #c7d2fe;
        }

        .success-message {
            background: #e8f7eb;
            color: #246b35;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .error-message {
            background: #fde8e8;
            color: #b42318;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th,
        td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #f4e0e7;
            vertical-align: middle;
        }

        th {
            background: #fce7ef;
            color: #923153;
            font-weight: 700;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #fffafd;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .badge-admin {
            display: inline-block;
            background: #fce7ef;
            color: #a83f68;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-customer {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .pagination-container {
            padding: 18px 24px;
        }
    </style>
</head>

<body>
    <div class="admin-container">

        <div class="admin-header">
            <div>
                <a class="back-link" href="{{ route('admin.orders') }}">← Back to Orders</a>
                <h1>User Management</h1>
            </div>

            <x-admin-nav />
        </div>

        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p class="error-message">{{ session('error') }}</p>
        @endif

        <div class="filter-card">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
                <button type="submit">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn-link btn-secondary">Clear</a>
                @endif
            </form>

            <a href="{{ route('admin.users.create') }}" class="btn-link" style="background: #a83f68;">
                ➕ Create New User
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 120px;">Role</th>
                        <th style="width: 130px;">Total Orders</th>
                        <th style="width: 180px;">Registered Date</th>
                        <th style="width: 170px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if(strtolower((string) $user->email) === strtolower((string) config('app.admin_email')))
                                <span class="badge-admin">Admin</span>
                            @else
                                <span class="badge-customer">Customer</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #a83f68;">{{ $user->orders_count }}</strong> orders
                        </td>
                        <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-link btn-edit">Edit</a>

                            @if(strtolower((string) $user->email) === strtolower((string) config('app.admin_email')) || $user->id === Auth::id())
                                <span style="font-size: 12px; color: #999; font-style: italic;">Protected</span>
                            @else
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete user {{ $user->name }} ({{ $user->email }})? Their past orders will be preserved.');"
                                      style="display: inline-block; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: #8e6573;">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="pagination-container">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</body>

</html>
