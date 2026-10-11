
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('Images/flamingo_pizza.svg') }}">
    
    <title>Customer Login - PapaPriza</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex;
            align-items: center; justify-content: center;
            background: #fff5f8; font-family: Arial, sans-serif;
            color: #6b3f4c; padding: 20px;
        }
        .box {
            width: 100%; max-width: 420px; background: white;
            padding: 32px; border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }
        h1 { text-align: center; color: #a83f68; margin-top: 0; }
        label { display: block; margin: 16px 0 6px; }
        input {
            width: 100%; padding: 12px;
            border: 1px solid #e5cbd5; border-radius: 8px;
        }
        button {
            width: 100%; margin-top: 24px; padding: 12px;
            border: none; border-radius: 8px; background: #a83f68;
            color: white; font-size: 16px; cursor: pointer;
        }
        .errors { color: #b42318; margin-bottom: 12px; }
        .link { text-align: center; margin-top: 18px; }
        a { color: #a83f68; }
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
    <div class="box">
        <img class="auth-logo" src="{{ asset('Images/flamingo_pizza.svg') }}" alt="PapaPriza logo">
        <h1>Welcome Back!</h1>
        <p style="text-align:center;">Log in to track your PapaPriza orders.</p>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login.submit') }}">
            @csrf

            <label for="email">Email Address</label>
            <input id="email" name="email" type="email"
                value="{{ old('email') }}" required autocomplete="email">

            <label for="password">Password</label>
            <input id="password" name="password" type="password"
                required autocomplete="current-password">

            <button type="submit">Log In</button>
        </form>

        <div class="link">
            New to PapaPriza?
            <a href="{{ route('customer.register') }}">Create an account</a>
        </div>
    </div>
</body>
</html>