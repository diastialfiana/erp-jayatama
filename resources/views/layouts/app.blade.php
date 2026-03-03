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

        /* Brand */
        .sb-brand {
            height: var(--nb-h);
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-decoration: none;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sb-logo {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #1d4ed8, #4F46E5);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.45);
        }

        .sb-logo svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: white;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sb-brand-text {
            overflow: hidden;
            min-width: 0;
        }

        .sb-brand-name {
            font-size: 0.98rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.03em;
            white-space: nowrap;
        }

        .sb-brand-sub {
            font-size: 0.58rem;
            color: rgba(255, 255, 255, 0.3);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        #sidebar.collapsed .sb-brand-text {
            opacity: 0;
            width: 0;
        }

        /* Nav */
        .sb-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 0;
        }

        .sb-nav::-webkit-scrollbar {
            width: 3px;
        }

        .sb-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 3px;
        }

        .sb-section-title {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: rgba(255, 255, 255, 0.2);
            padding: 10px 18px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }

        #sidebar.collapsed .sb-section-title {
            opacity: 0;
        }

        .sb-item {
            position: relative;
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 18px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.83rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            transition: all 0.2s var(--ease);
            white-space: nowrap;
            overflow: hidden;
            border-radius: 0;
        }

        .sb-link:hover {
            color: rgba(255, 255, 255, 0.9);
            background: var(--sb-hover);
        }

        .sb-link.active {
            color: #fff;
            background: var(--sb-active);
        }

        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #60a5fa, #818cf8);
            border-radius: 0 2px 2px 0;
        }

        .sb-icon {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sb-label {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }

        #sidebar.collapsed .sb-label,
        #sidebar.collapsed .sb-arrow,
        #sidebar.collapsed .sub-nav {
            opacity: 0;
            overflow: hidden;
            max-height: 0 !important;
        }

        .sb-arrow {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            transition: transform 0.25s;
            flex-shrink: 0;
        }

        .sb-item.open .sb-arrow {
            transform: rotate(180deg);
        }

        /* Sub Menu */
        .sub-nav {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.35s var(--ease);
        }

        .sb-item.open .sub-nav {
            max-height: 280px;
        }

        .sub-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 18px 8px 46px;
            color: rgba(255, 255, 255, 0.38);
            font-size: 0.79rem;
            text-decoration: none;
            font-weight: 400;
            transition: all 0.2s;
        }

        .sub-link::before {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            flex-shrink: 0;
        }

        .sub-link:hover {
            color: rgba(255, 255, 255, 0.85);
            background: rgba(255, 255, 255, 0.04);
        }

        /* Tooltip on collapsed */
        #sidebar.collapsed .sb-link {
            justify-content: center;
            padding: 12px;
        }

        #sidebar.collapsed .sb-icon {
            margin: 0;
        }

        /* Collapse btn */
        .sb-collapse-btn {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 12px 18px;
        }

        .sb-collapse-btn button {
            display: flex;
            align-items: center;
            gap: 9px;
            color: rgba(255, 255, 255, 0.28);
            font-size: 0.75rem;
            font-weight: 500;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            padding: 8px 0;
            transition: color 0.2s;
        }

        .sb-collapse-btn button:hover {
            color: rgba(255, 255, 255, 0.7);
        }

        .sb-collapse-btn button svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            flex-shrink: 0;
            transition: transform 0.3s;
        }

        #sidebar.collapsed .sb-collapse-btn button svg {
            transform: rotate(180deg);
        }

        #sidebar.collapsed .sb-collapse-btn button span {
            opacity: 0;
            width: 0;
            overflow: hidden;
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

            #sidebar.collapsed .sb-brand-text {
                opacity: 1;
                width: auto;
            }

            #sidebar.collapsed .sb-section-title {
                opacity: 1;
            }

            #sidebar.collapsed .sb-label,
            #sidebar.collapsed .sb-arrow {
                opacity: 1;
            }

            #sidebar.collapsed .sb-link {
                justify-content: flex-start;
                padding: 10px 18px;
            }

            #sidebar.collapsed .sb-collapse-btn button span {
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
        @include('layouts.sidebar')
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

        // Submenu toggles
        document.querySelectorAll('.sb-item.has-sub > .sb-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                link.closest('.sb-item').classList.toggle('open');
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
</body>

</html>