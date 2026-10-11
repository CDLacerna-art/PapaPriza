<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - PapaPriza Admin</title>

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

        .admin-nav-links {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
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
            padding: 24px 28px;
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .filter-card form {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            width: 100%;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            color: #6b3f4c;
        }

        select {
            padding: 10px 14px;
            border: 1px solid #e5bdcc;
            border-radius: 10px;
            background: white;
            color: #6b3f4c;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        button,
        .btn-reset {
            padding: 10px 20px;
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
            transition: background 0.2s ease;
        }

        button:hover,
        .btn-reset:hover {
            background: #b9436d;
        }

        .btn-secondary {
            background: #fce7ef;
            color: #923153;
        }

        .btn-secondary:hover {
            background: #f4d0de;
        }

        .table-wrapper {
            overflow-x: auto;
            padding-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th,
        td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #f4e0e7;
            vertical-align: top;
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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-module {
            background: #f1e2e8;
            color: #723447;
        }

        .badge-action-created,
        .badge-action-login,
        .badge-action-register {
            background: #e7f6ec;
            color: #1e6b3a;
        }

        .badge-action-updated {
            background: #fff3cd;
            color: #856404;
        }

        .badge-action-cancelled,
        .badge-action-deleted,
        .badge-action-logout {
            background: #fde8e8;
            color: #b42318;
        }

        .pagination-container {
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-container nav svg {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 600px) {
            body {
                padding: 18px 10px;
            }

            .admin-header {
                padding: 22px;
            }

            h1 {
                font-size: 27px;
            }

            .filter-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">

        <div class="admin-header">
            <div>
                <a class="back-link" href="{{ route('admin.orders') }}">← Back to Orders</a>
                <h1>Activity Logs &amp; Audit Trail</h1>
            </div>

            <x-admin-nav />
        </div>

        <div class="filter-card">
            <form method="GET" action="{{ route('admin.logs') }}">
                <div class="filter-group">
                    <label for="module">Module:</label>
                    <select name="module" id="module">
                        <option value="">All Modules</option>
                        @foreach ($modules as $mod)
                            <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>
                                {{ $mod }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="action">Action:</label>
                    <select name="action" id="action">
                        <option value="">All Actions</option>
                        @foreach ($actions as $act)
                            <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                                {{ ucfirst($act) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit">Apply Filter</button>

                @if(request('module') || request('action'))
                    <a href="{{ route('admin.logs') }}" class="btn-reset btn-secondary">Clear Filter</a>
                @endif
            </form>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>User</th>
                        <th style="width: 120px;">Module</th>
                        <th style="width: 120px;">Action</th>
                        <th>Description</th>
                        <th style="width: 130px;">IP Address</th>
                        <th style="width: 190px;">Date &amp; Time</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($logs as $log)
                    <tr>
                        <td>#{{ $log->id }}</td>
                        <td>
                            <strong>{{ $log->user_name }}</strong>
                            @if($log->user_id)
                                <span style="font-size: 12px; color: #8e6573; display: block;">User ID: {{ $log->user_id }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-module">{{ $log->module }}</span>
                        </td>
                        <td>
                            <span class="badge badge-action-{{ strtolower($log->action) }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ $log->description }}</td>
                        <td style="font-family: monospace; font-size: 13px;">{{ $log->ip_address ?? 'N/A' }}</td>
                        <td>{{ $log->created_at->format('M d, Y h:i:s A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: #8e6573;">
                            No activity logs recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($logs->hasPages())
                <div class="pagination-container">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>
</body>

</html>
