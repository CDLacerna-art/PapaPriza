<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - PapaPriza Admin</title>

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
            max-width: 800px;
            margin: 0 auto;
        }

        .admin-header,
        .form-card {
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
            font-size: 32px;
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

        .form-card {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #6b3f4c;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5bdcc;
            border-radius: 10px;
            background: white;
            color: #6b3f4c;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #d85b87;
        }

        .error-text {
            color: #b42318;
            font-size: 13px;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
        }

        button,
        .btn-link {
            padding: 12px 24px;
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

        .note {
            font-size: 12px;
            color: #8c4c62;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="admin-container">

        <div class="admin-header">
            <div>
                <a class="back-link" href="{{ route('admin.users.index') }}">← Back to Users</a>
                <h1>Edit User: {{ $user->name }}</h1>
            </div>

            <x-admin-nav />
        </div>

        <div class="form-card">
            <p style="margin-top: 0; color: #8c4c62; font-size: 14px;">
                User ID #{{ $user->id }} &bull; Total Orders Placed: <strong>{{ $user->orders_count }}</strong>
            </p>

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                    <p class="note">Only fill this field if you want to change the user's password (minimum 8 characters).</p>
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password if changing">
                </div>

                <div class="form-actions">
                    <button type="submit">Save Changes</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-link btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</body>

</html>
