 <nav>
    <a class="brand" href="{{ url('/') }}">
        <img class="nav-logo" src="{{ asset('Images/flamingo_pizza.svg') }}" alt="PapaPriza logo">
        PAPAPRIZA
    </a>

    <button class="nav-toggle" type="button" id="navToggle"
            aria-label="Toggle menu" aria-expanded="false" aria-controls="navLinks">&#9776;</button>

    <div class="nav-links" id="navLinks">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/menu') }}">Menu</a>
        <a href="{{ url('/contact') }}">Contact</a>

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
    </div>
</nav>

<script>
    (function () {
        var toggle = document.getElementById('navToggle');
        var links = document.getElementById('navLinks');
        if (!toggle || !links) return;

        toggle.addEventListener('click', function () {
            var open = links.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.innerHTML = open ? '&#10005;' : '&#9776;';
        });
    })();
</script>
