<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JES – @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #1E3A8A;
            --blue: #2563EB;
            --accent: #4F46E5;
            --bg: #F0F4FF;
            --sb-bg: #0F172A;
            --sb-hover: rgba(255, 255, 255, 0.06);
            --sb-active: rgba(79, 70, 229, 0.22);
            --sb-w: 258px;
            --sb-cw: 70px;
            --nb-h: 62px;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
            --radius: 14px;
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ══════════════ SIDEBAR ══════════════ */
        #sidebar {
            width: var(--sb-w);
            background: var(--sb-bg);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: width 0.3s var(--ease);
            overflow: hidden;
            z-index: 100;
            position: relative;
        }

        #sidebar.collapsed {
            width: var(--sb-cw);
        }

        #sidebar.collapsed {
            width: var(--sb-cw);
        }

        /* ══════════════ MAIN ══════════════ */
        .main-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }

        /* ══════════════ NAVBAR ══════════════ */
        .navbar {
            height: var(--nb-h);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 22px;
            gap: 14px;
            flex-shrink: 0;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
        }

        .hamburger {
            padding: 8px;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: 9px;
            color: var(--muted);
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .hamburger:hover {
            background: #f1f5f9;
            color: var(--text);
        }

        .hamburger svg {
            width: 19px;
            height: 19px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .nb-page-title {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text);
        }

        .nb-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Notification */
        .notif-btn {
            position: relative;
            padding: 9px;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: 9px;
            color: var(--muted);
            transition: all 0.2s;
        }

        .notif-btn:hover {
            background: #f1f5f9;
            color: var(--text);
        }

        .notif-btn svg {
            width: 19px;
            height: 19px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            display: block;
        }

        .notif-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(239, 68, 68, 0);
            }
        }

        /* User menu */
        .user-menu {
            position: relative;
        }

        .user-trigger {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 8px;
            border-radius: 10px;
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-trigger:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--blue), var(--accent));
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .user-details {
            text-align: left;
        }

        .user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
        }

        .user-role {
            font-size: 0.67rem;
            color: var(--muted);
            white-space: nowrap;
        }

        .user-chevron {
            width: 13px;
            height: 13px;
            stroke: #94a3b8;
            fill: none;
            stroke-width: 2;
            transition: transform 0.25s;
        }

        .user-menu.open .user-chevron {
            transform: rotate(180deg);
        }

        .dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            min-width: 190px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.97);
            transition: all 0.2s var(--ease);
            z-index: 200;
            overflow: hidden;
        }

        .user-menu.open .dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dd-header {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .dd-header strong {
            display: block;
            font-size: 0.83rem;
            color: var(--text);
            font-weight: 600;
        }

        .dd-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #374151;
            font-size: 0.81rem;
            text-decoration: none;
            transition: background 0.15s;
        }

        .dd-item:hover {
            background: #f8fafc;
        }

        .dd-item svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .dd-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 2px 0;
        }

        .dd-item.danger {
            color: #ef4444;
        }

        .dd-item.danger:hover {
            background: #fef2f2;
        }

        .dd-item.logout-form {
            padding: 0;
        }

        .dd-item.logout-form button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 16px;
            background: none;
            border: none;
            color: #ef4444;
            font-size: 0.81rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-align: left;
            transition: background 0.15s;
        }

        .dd-item.logout-form button:hover {
            background: #fef2f2;
        }

        /* ══════════════ CONTENT ══════════════ */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 26px;
            animation: fadeIn 0.4s var(--ease);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content::-webkit-scrollbar {
            width: 5px;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.84rem;
            margin-bottom: 20px;
        }

        /* ══════════════ OVERLAY (mobile) ══════════════ */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 99;
            backdrop-filter: blur(2px);
        }

        .overlay.show {
            display: block;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                transform: translateX(-100%);
                transition: transform 0.3s var(--ease);
                width: var(--sb-w) !important;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            #sidebar.collapsed .menu-title,
            #sidebar.collapsed .menu-arrow {
                opacity: 1;
            }

            #sidebar.collapsed .menu-toggle {
                justify-content: flex-start;
                padding: 10px 18px;
            }

            #sidebar.collapsed .sb-collapse-btn span {
                opacity: 1;
                width: auto;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar">
        <x-sidebar />
    </aside>

    <!-- MAIN -->
    <div class="main-wrap">
        @include('layouts.navbar')
        <div class="main-content">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const MOBILE = () => window.innerWidth <= 768;

        // Hamburger toggle
        document.getElementById('hamburger').addEventListener('click', () => {
            if (MOBILE()) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sb_collapsed', sidebar.classList.contains('collapsed'));
            }
        });

        // Restore collapsed state
        if (!MOBILE() && localStorage.getItem('sb_collapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }

        function closeSidebar() {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        }

        // Submenu toggles (New Structure)
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', (e) => {
                // If it's a link (e.g. Dashboard) don't prevent default unless it's just a toggle button
                if (btn.tagName.toLowerCase() !== 'a' && btn.getAttribute('href') === '#') {
                    e.preventDefault();
                } else if (btn.tagName.toLowerCase() === 'button' || !btn.getAttribute('href')) {
                    e.preventDefault();
                }

                // Close other menus (Accordion logic)
                document.querySelectorAll('.menu').forEach(m => {
                    if (m !== btn.parentElement) {
                        m.classList.remove('active');
                    }
                });

                // Toggle this menu
                btn.parentElement.classList.toggle('active');
            });
        });

        // User dropdown
        document.getElementById('userTrigger')?.addEventListener('click', function (e) {
            e.stopPropagation();
            document.getElementById('userMenuEl').classList.toggle('open');
        });
        document.addEventListener('click', () => {
            document.getElementById('userMenuEl')?.classList.remove('open');
        });

        // Collapse button inside sidebar
        document.getElementById('collapseSbBtn')?.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sb_collapsed', sidebar.classList.contains('collapsed'));
        });
    </script>

    @stack('scripts')
</body>

</html>