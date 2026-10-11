<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders &amp; Reports - PapaPriza</title>

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
        .order-summary,
        .table-wrapper,
        .reports-container {
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

        .admin-nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        h1,
        h2,
        h3 {
            color: #a83f68;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            margin: 12px 0 0;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            margin-top: 0;
            margin-bottom: 16px;
        }

        h3 {
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 14px;
        }

        .back-link {
            color: #c44776;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #923153;
        }

        .order-summary {
            padding: 24px 28px;
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .order-summary h2 {
            margin: 0;
            font-size: 20px;
        }

        .order-summary p {
            margin: 0;
            font-weight: 500;
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
            min-width: 1100px;
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

        select,
        input[type="date"] {
            padding: 10px 12px;
            border: 1px solid #e5bdcc;
            border-radius: 10px;
            background: white;
            color: #6b3f4c;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        button,
        .btn-link {
            padding: 10px 16px;
            border: none;
            border-radius: 25px;
            background: #d85b87;
            color: white;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
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

        /* REPORTS STYLING */
        .reports-container {
            padding: 28px;
        }

        .reports-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 22px;
            border-bottom: 2px solid #fce7ef;
            padding-bottom: 16px;
        }

        .reports-filter-form {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
            background: #fff5f8;
            padding: 16px 20px;
            border-radius: 14px;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .report-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .report-card {
            background: #fff5f8;
            padding: 22px;
            border-radius: 16px;
            border: 1px solid #f7d5e1;
            box-shadow: 0 4px 15px rgba(107, 63, 76, 0.04);
        }

        .report-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #8c4c62;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 8px 0;
        }

        .report-card-value {
            font-size: 30px;
            font-weight: 700;
            color: #a83f68;
            margin: 0;
            font-family: 'Playfair Display', serif;
        }

        .report-note {
            font-size: 13px;
            color: #8c4c62;
            margin-top: 8px;
            margin-bottom: 24px;
            font-style: italic;
        }

        .reports-tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 24px;
        }

        .report-table-box {
            background: white;
            border: 1px solid #f4e0e7;
            border-radius: 14px;
            padding: 20px;
            overflow-x: auto;
        }

        .report-table-box table {
            min-width: 100%;
        }

        .report-table-box th,
        .report-table-box td {
            padding: 12px 14px;
            font-size: 14px;
        }

        .report-meta-print {
            display: none;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
            color: #555;
            font-size: 13px;
        }

        /* PRINT STYLESHEET */
        @media print {
            body {
                background: white !important;
                color: black !important;
                padding: 15px !important;
                font-family: Arial, sans-serif !important;
            }

            .admin-container {
                max-width: 100% !important;
                margin: 0 !important;
            }

            /* Hide interactive elements, admin navigation, orders table, etc. */
            .back-link,
            .admin-nav-actions,
            .order-summary,
            .table-wrapper,
            .reports-filter-form,
            .btn-print,
            .success-message,
            .error-message,
            button,
            form {
                display: none !important;
            }

            .admin-header {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 0 10px 0 !important;
                border-bottom: 2px solid #333 !important;
                margin-bottom: 15px !important;
            }

            .admin-header h1 {
                font-size: 26px !important;
                color: black !important;
                margin: 0 !important;
            }

            .report-meta-print {
                display: block !important;
            }

            .reports-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin-bottom: 0 !important;
            }

            .reports-header h2 {
                color: black !important;
                font-size: 20px !important;
            }

            .report-cards-grid {
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 10px !important;
                margin-bottom: 20px !important;
            }

            .report-card {
                background: #f9f9f9 !important;
                border: 1px solid #ccc !important;
                padding: 12px !important;
                box-shadow: none !important;
            }

            .report-card-title {
                color: #333 !important;
                font-size: 11px !important;
            }

            .report-card-value {
                color: black !important;
                font-size: 22px !important;
            }

            .reports-tables-grid {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }

            .report-table-box {
                border: 1px solid #bbb !important;
                padding: 10px !important;
                page-break-inside: avoid !important;
            }

            table {
                width: 100% !important;
                min-width: 100% !important;
                border-collapse: collapse !important;
                page-break-inside: avoid !important;
            }

            th {
                background: #eee !important;
                color: black !important;
                border-bottom: 2px solid #666 !important;
            }

            td {
                border-bottom: 1px solid #ddd !important;
                color: black !important;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 18px 10px;
            }

            .admin-header {
                padding: 22px;
            }

            h1 {
                font-size: 27px;
            }

            .reports-tables-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">

        <div class="admin-header">
            <div>
                <a class="back-link" href="{{ url('/') }}">← Back to Home</a>
                <h1>PapaPriza Admin Orders &amp; Reports</h1>
            </div>

            <x-admin-nav />
        </div>

        {{-- Meta info shown ONLY in print preview --}}
        <div class="report-meta-print">
            <p><strong>PapaPriza Management Report</strong></p>
            <p>Generated on: {{ now()->timezone('Asia/Manila')->format('F d, Y h:i:s A') }} (PHT)</p>
            <p>Date Range Filter:
                @if($fromDate || $toDate)
                    {{ $fromDate ? 'From ' . \Carbon\Carbon::parse($fromDate)->format('M d, Y') : 'Start' }}
                    to
                    {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('M d, Y') : 'Present' }}
                @else
                    All Available Records (Last 14 Days daily trend)
                @endif
            </p>
        </div>

        @if (session('success'))
        <p class="success-message">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
        <div class="error-message">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="order-summary">
            <h2>Total Orders: {{ $orders->count() }}</h2>
            <p>
                Pending Orders:
                {{ $orders->where('status', 'Pending')->count() }}
            </p>
        </div>

        <!-- ============================================== -->
        <!-- ORDERS MANAGEMENT TABLE                        -->
        <!-- ============================================== -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Pizza</th>
                        <th>Price</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->address }}</td>
                        <td>{{ $order->pizza_name }}</td>
                        <td>₱{{ number_format($order->pizza_price, 2) }}</td>
                        <td>{{ $order->message }}</td>
                        <td>
                            @if ($order->status === 'Cancelled')
                            <strong style="color: #b42318;">Cancelled</strong>
                            @else
                            <form method="POST"
                                action="{{ route('admin.orders.updateStatus', $order->id) }}">
                                @csrf

                                <select name="status">
                                    <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Preparing" {{ $order->status === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                                    <option value="Cooking" {{ $order->status === 'Cooking' ? 'selected' : '' }}>Cooking</option>
                                    <option value="Delivery" {{ $order->status === 'Delivery' ? 'selected' : '' }}>Delivery</option>
                                    <option value="Completed" {{ $order->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>

                                <button type="submit">Save Status</button>
                            </form>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <!-- TASK 2: Delete Order with Confirm Popup -->
                            <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete order #{{ $order->id }}?');"
                                style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 30px;">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- ============================================== -->
        <!--                 READ-ONLY REPORTS MODULE       -->
        <!-- ============================================== -->
        <section class="reports-container">
            <div class="reports-header">
                <div>
                    <h2>Daily Sales</h2>
                    <p style="margin: 0; color: #8c4c62; font-size: 14px;">Business metrics, daily sales performance, and top selling items.</p>
                </div>
                <button type="button" class="btn-link btn-print" onclick="window.print()">
                    🖨️ Print Report
                </button>
            </div>

            <!-- Date Range Filter Form -->
            <form method="GET" action="{{ route('admin.orders') }}" class="reports-filter-form">
                <div class="filter-item">
                    <label for="from">From Date:</label>
                    <input type="date" id="from" name="from" value="{{ request('from', $fromDate) }}">
                </div>

                <div class="filter-item">
                    <label for="to">To Date:</label>
                    <input type="date" id="to" name="to" value="{{ request('to', $toDate) }}">
                </div>

                <button type="submit">Filter Range</button>

                @if(request('from') || request('to'))
                    <a href="{{ route('admin.orders') }}" class="btn-link btn-secondary">Reset Range</a>
                @endif
            </form>

            <!-- Summary Cards -->
            <div class="report-cards-grid">
                <div class="report-card">
                    <p class="report-card-title">Total Orders</p>
                    <p class="report-card-value">{{ number_format($reports['total_orders']) }}</p>
                </div>

                <div class="report-card">
                    <p class="report-card-title">Total Sales</p>
                    <p class="report-card-value">₱{{ number_format($reports['total_sales'], 2) }}</p>
                </div>

                <div class="report-card">
                    <p class="report-card-title">Completed Orders</p>
                    <p class="report-card-value">{{ number_format($reports['status_counts']['Completed'] ?? 0) }}</p>
                </div>

                <div class="report-card">
                    <p class="report-card-title">Cancelled Orders</p>
                    <p class="report-card-value">{{ number_format($reports['status_counts']['Cancelled'] ?? 0) }}</p>
                </div>
            </div>

            <p class="report-note">* Note: Cancelled orders are not counted in total sales.</p>

            <!-- Reports Tables Grid -->
            <div class="reports-tables-grid">
                <!-- Orders Per Day Table -->
                <div class="report-table-box">
                    <h3>Orders Per Day</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Orders</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['orders_per_day'] as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day->order_date)->format('M d, Y (D)') }}</td>
                                <td><strong>{{ $day->total_orders }}</strong></td>
                                <td>₱{{ number_format($day->total_sales, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: #8c4c62; padding: 20px;">
                                    No sales data recorded for this date range.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Top 5 Items Table -->
                <div class="report-table-box">
                    <h3>Top Items (Top 5)</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Pizza</th>
                                <th>Orders</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['top_items'] as $item)
                            <tr>
                                <td><strong>{{ $item->pizza_name }}</strong></td>
                                <td>{{ $item->order_count }}</td>
                                <td>₱{{ number_format($item->total_sales, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: #8c4c62; padding: 20px;">
                                    No menu items sold in this period.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</body>

</html>