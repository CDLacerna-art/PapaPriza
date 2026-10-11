
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15">
    <title>My Orders - PapaPriza</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('Images/flamingo_pizza.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            background: #fff5f8;
            color: #6b3f4c;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header, .order, .empty {
            background: #fff;
            padding: 28px;
            border-radius: 18px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(107, 63, 76, 0.08);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        h1, .order h2, .empty h2 {
            color: #a83f68;
        }

        h1, .order h2, .empty h2 {
            font-family: 'Playfair Display', serif;
        }

        h1 {
            margin: 0;
            font-size: 32px;
        }

        .order h2, .empty h2 {
            margin-top: 0;
        }

        p {
            line-height: 1.7;
        }

        .status {
            display: inline-block;
            background: #fce7ef;
            color: #a83f68;
            padding: 9px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
        }

        a {
            color: #c44776;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        a:hover {
            color: #923153;
        }

        .button {
            display: inline-block;
            padding: 11px 22px;
            background: #d85b87;
            color: white;
            border: none;
            border-radius: 30px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .button:hover {
            background: #b9436d;
            color: white;
            transform: translateY(-1px);
        }

        .empty {
            text-align: center;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 12px;
            }

            .header, .order, .empty {
                padding: 22px;
            }

            h1 {
                font-size: 26px;
            }
        }

.status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 42px;
    min-width: 130px;
    padding: 0 20px;
    border-radius: 25px;
    background: #fce7ef;
    color: #a83f68;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 14px;
    line-height: 1;
    border: none;
}

.status-row {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.cancel-button {
    cursor: pointer;
    background: #fde8e8;
    color: #b42318;
    transition: background 0.2s ease, color 0.2s ease;
}

.cancel-button:hover {
    background: #b42318;
    color: white;
}

.status-cancelled {
    background: #fde8e8;
    color: #b42318;
}

.alert {
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-success {
    background: #e7f6ec;
    color: #1e6b3a;
}

.alert-error {
    background: #fde8e8;
    color: #b42318;
}
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="topbar">
                <h1>My PapaPriza Orders</h1>

                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button class="button" type="submit">Log Out</button>
                </form>
            </div>

            <p>Welcome, {{ Auth::user()->name }}!</p>
            <p>Your order status refreshes automatically every 15 seconds.</p>
            <a href="{{ url('/menu') }}">Order more pizza</a>
        </div>

        @forelse ($orders as $order)
            <div class="order">
                <h2>{{ $order->pizza_name }}</h2>

                <p><strong>Order number:</strong> #{{ $order->id }}</p>
                <p><strong>Price:</strong> ₱{{ number_format($order->pizza_price, 2) }}</p>
                <p><strong>Ordered on:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>

                <p><strong>Current status:</strong></p>
                
<div class="status-row">
    <span class="status {{ $order->status === 'Cancelled' ? 'status-cancelled' : '' }}">{{ $order->status }}</span>

    @if ($order->status === 'Pending')
        <form method="POST" action="{{ route('customer.orders.cancel', $order) }}"
              onsubmit="return confirm('Cancel this order?');" style="margin: 0;">
            @csrf
            <button type="submit" class="status cancel-button">Cancel Order</button>
        </form>
    @endif
</div>
</div>
        @empty
            <div class="empty">
                <h2>No orders yet</h2>
                <p>Your pizza orders will appear here after you place them while logged in.</p>
                <a class="button" href="{{ url('/menu') }}">Browse the Menu</a>
            </div>
        @endforelse
    </div>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
    @endif
</body>
</html>