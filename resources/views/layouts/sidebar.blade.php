<style>
    .sidebar-brand {
        padding: 20px;
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
        border-bottom: 1px solid #4f5962;
    }

    .nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        border-bottom: 1px solid #4f5962;
    }

    .nav-link {
        display: block;
        padding: 15px 20px;
        color: #c2c7d0;
        text-decoration: none;
        transition: background 0.2s;
    }

    .nav-link:hover {
        background: #4f5962;
        color: #fff;
    }

    .nav-header {
        padding: 15px 20px 5px;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #8a93a2;
        font-weight: bold;
    }

    .sub-menu {
        list-style: none;
        padding-left: 20px;
        background: #2a3035;
        display: none;
    }

    .has-submenu:hover .sub-menu {
        display: block;
    }
</style>

<div class="sidebar">
    <div class="sidebar-brand">
        JAYATAMA
    </div>

    <ul class="nav-list">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>

        <li class="nav-item has-submenu">
            <a href="#" class="nav-link">📦 Inventory And GA</a>
            <ul class="sub-menu">
                <li><a href="{{ route('inventory.index') }}" class="nav-link">Overview</a></li>
            </ul>
        </li>

        <li class="nav-item has-submenu">
            <a href="#" class="nav-link">💰 Finance</a>
            <ul class="sub-menu">
                <li><a href="{{ route('finance.index') }}" class="nav-link">Overview</a></li>
            </ul>
        </li>

        <li class="nav-item has-submenu">
            <a href="#" class="nav-link">📊 Accounting</a>
            <ul class="sub-menu">
                <li><a href="{{ route('accounting.index') }}" class="nav-link">Overview</a></li>
            </ul>
        </li>

        @role('Superadmin')
        <li class="nav-item has-submenu">
            <a href="#" class="nav-link">🛠 Administrator</a>
            <ul class="sub-menu">
                <li><a href="{{ route('administrator.index') }}" class="nav-link">Manajemen User</a></li>
                <li><a href="{{ route('administrator.index') }}" class="nav-link">Role & Permission</a></li>
                <li><a href="{{ route('administrator.index') }}" class="nav-link">Master Pegawai</a></li>
                <li><a href="{{ route('administrator.index') }}" class="nav-link">Master Divisi</a></li>
                <li><a href="{{ route('administrator.index') }}" class="nav-link">Master Jabatan</a></li>
            </ul>
        </li>
        @endrole

        <li class="nav-item">
            <a href="{{ route('help.index') }}" class="nav-link">❓ Help</a>
        </li>
    </ul>
</div>