<div class="top-navbar">
    <div class="brand" style="font-weight: bold; font-size: 1.2rem; color: #333;">
        ERP JAYATAMA
    </div>

    <div class="user-menu">
        <div class="dropdown">
            <span style="font-weight: 500; cursor: pointer; padding: 10px;">
                {{ auth()->user()->nama_lengkap }}
                <span
                    style="font-size: 0.8em; color: #6c757d;">({{ auth()->user()->roles->pluck('name')->implode(', ') }})</span>
                ▼
            </span>
            <div class="dropdown-content">
                <a href="#">Profile</a>
                <hr style="margin: 0; border-top: 1px solid #eee;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>