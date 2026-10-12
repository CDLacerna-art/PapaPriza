 @auth
    @if (strtolower((string) Auth::user()->email) === strtolower((string) config('app.admin_email')))
        <a href="{{ route('admin.orders') }}" style="color: #a83f68; font-weight: 700;">Admin Panel</a>
        <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Admin Logout</button>
        </form>
    @else
        <a href="{{ route('customer.orders') }}">My Orders</a>
        <form action="{{ route('customer.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endif
@else
    <a href="{{ route('customer.login') }}">Customer Login</a>
    <a href="{{ route('customer.register') }}">Create Account</a>
@endauth