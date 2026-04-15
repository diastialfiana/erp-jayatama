<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JES – @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            --ribbon-skin-color: #4671a8;
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
            max-height: 600px;
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
        /* ══════════════ WINDOW BAR ══════════════ */
        .window-title-bar {
            background: linear-gradient(
                to bottom,
                var(--ribbon-skin-color, #1e3a8a),
                color-mix(in srgb, var(--ribbon-skin-color, #1e3a8a) 80%, black)
            );
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            padding: 5px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            border-bottom: 1px solid rgba(0,0,0,0.15);
            transition: background 0.3s ease;
            user-select: none;
        }

        .window-title-bar span,
        .window-title-bar svg {
            color: inherit;
            fill: currentColor;
        }

        .window-title-bar .nav-btn {
            opacity: 0.85;
            transition: opacity 0.15s;
        }
        .window-title-bar .nav-btn:hover {
            opacity: 1;
        }

        /* ══════════════ RIBBON TOOLBAR ══════════════ */
        .ribbon-toolbar {
            display: flex;
            background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #cbd5e1;
            padding: 2px 4px;
            gap: 2px;
            user-select: none;
            flex-shrink: 0;
        }

        .ribbon-group {
            display: flex;
            flex-direction: column;
            border-right: 1px solid #cbd5e1;
            padding: 2px 6px;
            align-items: center;
        }

        .ribbon-group:last-child {
            border-right: none;
        }

        .ribbon-actions {
            display: flex;
            gap: 4px;
            align-items: flex-start;
            flex: 1;
        }

        .ribbon-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4px 8px;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.15s var(--ease);
            min-width: 48px;
        }

        .ribbon-btn:hover {
            background: rgba(37, 99, 235, 0.08);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .ribbon-btn:active {
            background: rgba(37, 99, 235, 0.15);
            transform: translateY(1px);
        }

        .ribbon-btn svg {
            width: 24px;
            height: 24px;
            margin-bottom: 2px;
            color: #475569;
            transition: color 0.15s;
        }

        .ribbon-btn:hover svg {
            color: var(--blue);
        }

        .ribbon-btn span {
            font-size: 0.68rem;
            font-weight: 500;
            color: #475569;
            line-height: 1;
        }

        .ribbon-btn.small {
            flex-direction: row;
            gap: 6px;
            padding: 3px 8px;
            min-width: auto;
            justify-content: flex-start;
            width: 100%;
        }

        .ribbon-btn.small svg {
            width: 14px;
            height: 14px;
            margin-bottom: 0;
        }

        .ribbon-group-label {
            margin-top: 4px;
            font-size: 0.6rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .ribbon-grid {
            display: grid;
            grid-template-rows: repeat(3, 1fr);
            gap: 1px;
        }

        .skin-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 4px;
            padding: 4px;
        }

        .skin-item {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: transform 0.15s;
        }

        .skin-item:hover {
            transform: scale(1.1);
            border-color: var(--blue);
        }

        /* ══════════════ FIND / SEARCH DIALOG ══════════════ */
        .erp-find-overlay {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 9000;
            pointer-events: none;
        }

        .erp-find-panel {
            position: fixed;
            top: 80px;
            right: 30px;
            width: 420px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18), 0 4px 16px rgba(0, 0, 0, 0.08);
            pointer-events: all;
            animation: erpFindSlideIn 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        @keyframes erpFindSlideIn {
            from { opacity: 0; transform: translateY(-12px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .erp-find-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 16px;
            background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
        }

        .erp-find-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
        }

        .erp-find-title svg {
            color: #2563eb;
        }

        .erp-find-close {
            width: 26px;
            height: 26px;
            border: none;
            background: transparent;
            color: #94a3b8;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .erp-find-close:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .erp-find-body {
            padding: 14px 16px;
        }

        .erp-find-input-row {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .erp-find-input {
            flex: 1;
            padding: 8px 12px;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .erp-find-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .erp-find-input.has-results {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .erp-find-input.no-results {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .erp-find-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .erp-find-btn:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
            color: #1e293b;
        }

        .erp-find-btn:active {
            transform: scale(0.95);
        }

        .erp-find-options {
            display: flex;
            gap: 16px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
        }

        .erp-find-checkbox {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            color: #64748b;
            cursor: pointer;
            user-select: none;
        }

        .erp-find-checkbox input {
            width: 14px;
            height: 14px;
            cursor: pointer;
            accent-color: #2563eb;
        }

        .erp-find-status {
            margin-top: 8px;
            font-size: 0.75rem;
            color: #64748b;
            min-height: 18px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .erp-find-status .match-count {
            font-weight: 600;
            color: #2563eb;
        }

        .erp-find-status .no-match {
            font-weight: 600;
            color: #ef4444;
        }

        /* Search highlight styles applied to matching table rows/cells */
        tr.erp-find-match td {
            background: #fef9c3 !important;
        }

        tr.erp-find-match-active td {
            background: #fbbf24 !important;
            outline: 2px solid #f59e0b;
            outline-offset: -1px;
        }

        .erp-find-highlight {
            background: #fef08a;
            color: #1e293b;
            border-radius: 2px;
            padding: 0 1px;
            box-shadow: 0 0 0 1px #fbbf24;
        }

        .erp-find-highlight-active {
            background: #f97316;
            color: #ffffff;
            box-shadow: 0 0 0 2px #ea580c;
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

    @stack('scripts')
    <script>
        // Global Skin Management
        document.addEventListener('change-skin', (e) => {
            const color = e.detail;
            document.documentElement.style.setProperty('--ribbon-skin-color', color);
            localStorage.setItem('erp_ribbon_skin', color);
            showToast('Skin updated successfully', 'success');
        });

        // Global Toast System
        window.showToast = function(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: white;
                padding: 12px 24px;
                border-radius: 8px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                border-left: 4px solid ${type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6')};
                z-index: 9999;
                font-size: 0.85rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 10px;
                transform: translateY(100px);
                transition: transform 0.3s ease;
            `;
            
            toast.innerHTML = `
                <span style="color: ${type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6')}">
                    ${type === 'success' ? '✓' : (type === 'error' ? '✕' : 'ℹ')}
                </span>
                <span>${message}</span>
            `;

            document.body.appendChild(toast);
            setTimeout(() => toast.style.transform = 'translateY(0)', 10);
            
            setTimeout(() => {
                toast.style.transform = 'translateY(100px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };

        // Global JSON Exporter
        window.exportToJSONFile = function(data, filename) {
            const jsonStr = JSON.stringify(data, null, 2);
            const blob = new Blob([jsonStr], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename || 'export.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        };

        // Initialize Skin on Load
        document.addEventListener('DOMContentLoaded', () => {
            const savedSkin = localStorage.getItem('erp_ribbon_skin');
            if (savedSkin) {
                document.documentElement.style.setProperty('--ribbon-skin-color', savedSkin);
            }
        });

        // ══════════════ GLOBAL FIND / SEARCH SYSTEM ══════════════
        (function() {
            let _findMatchRows = [];
            let _findCurrentIdx = -1;
            let _findDebounce = null;

            // Open Find Dialog
            window.erpFindOpen = function() {
                var dialog = document.getElementById('erpFindDialog');
                if (!dialog) return;
                // Show the dialog
                dialog.style.display = 'block';
                // Replay animation
                var panel = dialog.querySelector('.erp-find-panel');
                if (panel) { panel.style.animation = 'none'; void panel.offsetHeight; panel.style.animation = ''; }
                // Focus input after display:block takes effect
                setTimeout(function() {
                    var inp = document.getElementById('erpFindInput');
                    if (inp) { inp.focus(); inp.select(); }
                }, 80);
            };

            // Close Find Dialog
            window.erpFindClose = function() {
                var dialog = document.getElementById('erpFindDialog');
                if (!dialog) return;
                dialog.style.display = 'none';
                erpFindClearHighlights();
                _findMatchRows = [];
                _findCurrentIdx = -1;
                var inp = document.getElementById('erpFindInput');
                if (inp) { inp.value = ''; inp.classList.remove('has-results', 'no-results'); }
                var status = document.getElementById('erpFindStatus');
                if (status) status.innerHTML = '';
            };

            // Toggle Find Dialog
            window.erpFindToggle = function() {
                const dialog = document.getElementById('erpFindDialog');
                if (!dialog) return;
                if (dialog.style.display === 'none' || dialog.style.display === '') {
                    erpFindOpen();
                } else {
                    erpFindClose();
                }
            };

            // Clear all highlights
            function erpFindClearHighlights() {
                // Remove row highlights
                document.querySelectorAll('.erp-find-match, .erp-find-match-active').forEach(el => {
                    el.classList.remove('erp-find-match', 'erp-find-match-active');
                });
            }
            window.erpFindClearHighlights = erpFindClearHighlights;

            // Check real visibility including Alpine x-show (checks computed style)
            function _isVisible(el) {
                if (!el) return false;
                let node = el;
                while (node && node !== document.body) {
                    var st = window.getComputedStyle(node);
                    if (st.display === 'none' || st.visibility === 'hidden') return false;
                    node = node.parentElement;
                }
                return true;
            }

            // Execute search against all visible table rows
            function _erpFindExecute() {
                var inputEl   = document.getElementById('erpFindInput');
                var statusEl  = document.getElementById('erpFindStatus');
                var caseEl    = document.getElementById('erpFindCase');
                var hlEl      = document.getElementById('erpFindHighlightAll');
                var caseSensitive = caseEl ? caseEl.checked : false;
                var highlightAll  = hlEl  ? hlEl.checked  : true;
                var query = inputEl ? inputEl.value.trim() : '';

                // Clear previous results using the correct function name
                erpFindClearHighlights();
                _findMatchRows = [];
                _findCurrentIdx = -1;

                if (!query) {
                    if (statusEl) statusEl.innerHTML = '';
                    if (inputEl) inputEl.classList.remove('has-results', 'no-results');
                    return;
                }

                var qLow = caseSensitive ? query : query.toLowerCase();

                document.querySelectorAll('table tbody tr').forEach(function(row) {
                    if (!_isVisible(row)) return;
                    var cells = row.querySelectorAll('td');
                    if (!cells.length) return;
                    var txt = '';
                    cells.forEach(function(c){ txt += ' ' + (c.textContent||''); });
                    var cmp = caseSensitive ? txt : txt.toLowerCase();
                    if (cmp.indexOf(qLow) !== -1) _findMatchRows.push(row);
                });

                if (_findMatchRows.length > 0) {
                    if (highlightAll) {
                        _findMatchRows.forEach(function(r){ r.classList.add('erp-find-match'); });
                    }
                    _findCurrentIdx = 0;
                    _activateMatch(0);
                    if (statusEl) statusEl.innerHTML = '<span class="match-count">'+_findMatchRows.length+'</span> hasil ditemukan &mdash; gunakan ▲▼ untuk navigasi';
                    if (inputEl) { inputEl.classList.add('has-results'); inputEl.classList.remove('no-results'); }
                } else {
                    if (statusEl) statusEl.innerHTML = '<span class="no-match">Tidak ditemukan</span> untuk "' + _escHtml(query) + '"';
                    if (inputEl) { inputEl.classList.add('no-results'); inputEl.classList.remove('has-results'); }
                }
            }

            function _escHtml(str) {
                return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            }

            function _activateMatch(idx) {
                document.querySelectorAll('.erp-find-match-active').forEach(function(el){
                    el.classList.remove('erp-find-match-active');
                });
                if (idx < 0 || idx >= _findMatchRows.length) return;
                var row = _findMatchRows[idx];
                row.classList.add('erp-find-match-active');
                row.classList.add('erp-find-match');
                // Scroll into scrollable ancestor
                try {
                    row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } catch(e) { row.scrollIntoView(false); }
                var statusEl = document.getElementById('erpFindStatus');
                if (statusEl) statusEl.innerHTML = 'Hasil <span class="match-count">'+(idx+1)+'</span> dari <span class="match-count">'+_findMatchRows.length+'</span>';
            }

            window.erpFindNav = function(direction) {
                if (_findMatchRows.length === 0) { _erpFindExecute(); return; }
                _findCurrentIdx += direction;
                if (_findCurrentIdx >= _findMatchRows.length) _findCurrentIdx = 0;
                if (_findCurrentIdx < 0) _findCurrentIdx = _findMatchRows.length - 1;
                _activateMatch(_findCurrentIdx);
            };

            // Attach input listeners
            document.addEventListener('DOMContentLoaded', function() {
                var inp = document.getElementById('erpFindInput');
                if (inp) {
                    inp.addEventListener('input', function() {
                        clearTimeout(_findDebounce);
                        _findDebounce = setTimeout(_erpFindExecute, 250);
                    });
                    inp.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') { e.preventDefault(); erpFindNav(e.shiftKey ? -1 : 1); }
                        else if (e.key === 'Escape') { e.preventDefault(); erpFindClose(); }
                    });
                }
                var ce = document.getElementById('erpFindCase');
                var hl = document.getElementById('erpFindHighlightAll');
                if (ce) ce.addEventListener('change', _erpFindExecute);
                if (hl) hl.addEventListener('change', _erpFindExecute);
            });

            // Ctrl+F shortcut
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    if (document.querySelector('.ribbon-toolbar')) {
                        e.preventDefault();
                        erpFindOpen();
                    }
                }
                if (e.key === 'Escape') {
                    var dlg = document.getElementById('erpFindDialog');
                    if (dlg && dlg.style.display !== 'none') erpFindClose();
                }
            });

            // Listen for Alpine $dispatch('ribbon-action', 'find').
            // Alpine $dispatch creates a CustomEvent that bubbles UP to window.
            // We MUST listen on window (not just document) to reliably catch it.
            window.addEventListener('ribbon-action', function(e) {
                if (e && e.detail === 'find') erpFindOpen();
            });
            document.addEventListener('ribbon-action', function(e) {
                if (e && e.detail === 'find') erpFindOpen();
            });

        })();
    </script>

    <!-- ══ GLOBAL FIND / SEARCH DIALOG ══ -->
    <div id="erpFindDialog" class="erp-find-overlay" style="display:none;">
        <div class="erp-find-panel">
            <div class="erp-find-header">
                <div class="erp-find-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>Find &amp; Search</span>
                </div>
                <button class="erp-find-close" onclick="erpFindClose()" title="Close (Esc)">&times;</button>
            </div>
            <div class="erp-find-body">
                <div class="erp-find-input-row">
                    <input type="text" id="erpFindInput" class="erp-find-input" placeholder="Type to search across all visible data..." autocomplete="off" spellcheck="false">
                    <button class="erp-find-btn erp-find-btn-prev" onclick="erpFindNav(-1)" title="Previous Match (Shift+Enter)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
                    </button>
                    <button class="erp-find-btn erp-find-btn-next" onclick="erpFindNav(1)" title="Next Match (Enter)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                </div>
                <div class="erp-find-options">
                    <label class="erp-find-checkbox"><input type="checkbox" id="erpFindCase"> Case sensitive</label>
                    <label class="erp-find-checkbox"><input type="checkbox" id="erpFindHighlightAll" checked> Highlight all</label>
                </div>
                <div class="erp-find-status" id="erpFindStatus"></div>
            </div>
        </div>
    </div>
</body>
</html>