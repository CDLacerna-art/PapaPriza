
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PapaPriza</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('Images/flamingo_pizza.svg') }}">
    
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff5f8;
            font-family: Arial, sans-serif;
            color: #6b3f4c;
        }

        .login-box {
            width: 90%;
            max-width: 400px;
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #a83f68;
            margin-top: 0;
        }

        p {
            text-align: center;
            color: #806671;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e5cbd5;
            border-radius: 8px;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #a83f68;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .error {
            color: #b42318;
            font-size: 14px;
            margin-top: 12px;
        }
        .auth-logo {
            display: block;
            margin: 0 auto 16px;
            width: 90px;
            height: 90px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img class="auth-logo" src="{{ asset('Images/flamingo_pizza.svg') }}" alt="PapaPriza logo">
        <h1>PapaPriza</h1>
        <p>Admin Login</p>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <label for="email">Admin Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
            >

            <button type="submit">Log In</button>
        </form>
    </div>
</body>
</html>