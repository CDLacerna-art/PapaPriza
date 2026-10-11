<style>
    .admin-nav-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .admin-nav-actions .nav-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border: none;
        border-radius: 999px;
        background: #fce7ef;
        color: #923153;
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.2;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .admin-nav-actions .nav-pill:hover {
        background: #f4d0de;
    }

    .admin-nav-actions .nav-pill--active {
        background: #a83f68;
        color: #fff;
    }

    .admin-nav-actions .nav-pill--active:hover {
        background: #923153;
    }

    .admin-nav-actions .nav-logout {
        background: #6b3f4c;
        color: #fff;
    }

    .admin-nav-actions .nav-logout:hover {
        background: #55303b;
    }
</style>

<div class="admin-nav-actions">
    <a href="{{ route('admin.orders') }}"
       class="nav-pill {{ request()->routeIs('admin.orders') ? 'nav-pill--active' : '' }}">
        Orders &amp; Reports
    </a>
    <a href="{{ route('admin.logs') }}"
       class="nav-pill {{ request()->routeIs('admin.logs') ? 'nav-pill--active' : '' }}">
        Activity Logs
    </a>
    <a href="{{ route('admin.users.index') }}"
       class="nav-pill {{ request()->routeIs('admin.users.*') ? 'nav-pill--active' : '' }}">
        Users
    </a>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0; display: inline;">
        @csrf
        <button type="submit" class="nav-pill nav-logout">Log Out</button>
    </form>
</div>