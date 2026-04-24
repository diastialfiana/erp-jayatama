@extends('layouts.app')

@section('content')
<style>
/* CSS Reset for toggles */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 38px;
    height: 20px;
}
.toggle-switch input { 
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(255, 255, 255, 0.1);
    transition: .3s;
    border-radius: 34px;
    border: 1px solid rgba(255,255,255,0.2);
}
.slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #3b82f6;
    border-color: #3b82f6;
}
input:checked + .slider:before {
    transform: translateX(18px);
}
/* Modern Matrix Table */
.matrix-container {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
}
.matrix-table {
    width: 100%;
    border-collapse: collapse;
}
.matrix-table th, .matrix-table td {
    padding: 12px 16px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.8);
    font-size: 0.85rem;
}
.matrix-table th {
    background: rgba(255,255,255,0.02);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    color: rgba(255,255,255,0.5);
}
.matrix-table td:first-child, .matrix-table th:first-child {
    text-align: left;
    font-weight: 500;
}
.matrix-table tr:hover td {
    background: rgba(255,255,255,0.02);
}

/* Header & Search */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 20px;
}
.search-box {
    padding: 8px 12px;
    border-radius: 6px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: white;
    font-size: 0.85rem;
    width: 250px;
    outline: none;
}
.search-box:focus {
    border-color: #3b82f6;
}

/* Toast */
.toast-msg {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #10b981;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 9999;
}
.toast-msg.show {
    transform: translateY(0);
    opacity: 1;
}

/* Loading overlay */
.loading-indicator {
    display: none;
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin { 100% { transform: translate(-50%, -50%) rotate(360deg); } }

td.processing .slider { opacity: 0.5; pointer-events: none; }
td.processing .loading-indicator { display: block; }
</style>

<div class="container-fluid py-4">
    <div class="page-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: #fff;">Menu Visibility</h2>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.85rem; margin: 5px 0 0 0;">Manage sidebar menu access for each user.</p>
        </div>
        <div>
            <input type="text" id="searchInput" class="search-box" placeholder="Search user...">
        </div>
    </div>

    <div class="matrix-container">
        <table class="matrix-table" id="matrixTable">
            <thead>
                <tr>
                    <th>User</th>
                    @foreach($menus as $menu)
                        <th>{{ $menu->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <!-- Super Admin row (Read-only representation) -->
                <tr>
                    <td>
                        <div style="display:flex; flex-direction: column;">
                            <span style="color:#fff; font-weight:600;">Super Administrator</span>
                            <span style="color:rgba(255,255,255,0.4); font-size:0.75rem;">super_admin</span>
                        </div>
                    </td>
                    @foreach($menus as $menu)
                        <td>
                            <div class="toggle-switch" style="opacity:0.6; cursor:not-allowed;" title="Super Admin always has access">
                                <input type="checkbox" checked disabled>
                                <span class="slider"></span>
                            </div>
                        </td>
                    @endforeach
                </tr>

                <!-- User rows -->
                @foreach($users as $user)
                    <tr class="user-row">
                        <td class="user-name-col">
                            <div style="display:flex; flex-direction: column;">
                                <span style="color:#fff; font-weight:500;" class="u-name">{{ $user->name }}</span>
                                <span style="color:rgba(255,255,255,0.4); font-size:0.75rem;">{{ $user->username }}</span>
                            </div>
                        </td>
                        @foreach($menus as $menu)
                            @php
                                $hasAccess = false;
                                if(isset($permissions[$user->id])) {
                                    $perm = $permissions[$user->id]->firstWhere('menu_id', $menu->id);
                                    if($perm && $perm->can_view) {
                                        $hasAccess = true;
                                    }
                                }
                            @endphp
                            <td style="position:relative;">
                                <label class="toggle-switch">
                                    <input type="checkbox" 
                                           class="permission-toggle" 
                                           data-user="{{ $user->id }}" 
                                           data-menu="{{ $menu->id }}"
                                           {{ $hasAccess ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                                <div class="loading-indicator"></div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="toastMsg" class="toast-msg">
    <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><polyline points="20 6 9 17 4 12"/></svg>
    <span>Access updated</span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const userRows = document.querySelectorAll('.user-row');

    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        userRows.forEach(row => {
            const name = row.querySelector('.u-name').textContent.toLowerCase();
            if (name.includes(term)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // AJAX Toggle
    const toggles = document.querySelectorAll('.permission-toggle');
    const toast = document.getElementById('toastMsg');
    let toastTimeout;

    function showToast() {
        toast.classList.add('show');
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    toggles.forEach(toggle => {
        toggle.addEventListener('change', async function() {
            const userId = this.getAttribute('data-user');
            const menuId = this.getAttribute('data-menu');
            const canView = this.checked ? 1 : 0;
            const td = this.closest('td');

            td.classList.add('processing');

            try {
                const response = await fetch("{{ route('administrator.menu-visibility.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        menu_id: menuId,
                        can_view: canView
                    })
                });

                const data = await response.json();
                
                if(data.success) {
                    showToast();
                } else {
                    alert('Failed to update permission.');
                    this.checked = !this.checked; // revert
                }
            } catch(e) {
                console.error(e);
                alert('An error occurred.');
                this.checked = !this.checked; // revert
            } finally {
                td.classList.remove('processing');
            }
        });
    });
});
</script>
@endsection
