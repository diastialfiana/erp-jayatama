<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JES – @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* Core Palette */
            --c-primary:   #1e293b;
            --c-secondary: #0f172a;
            --c-accent:    #3b82f6;
            --c-success:   #22c55e;
            --c-danger:    #ef4444;
            --c-warning:   #f59e0b;
            --c-bg:        #f1f5f9;
            --c-card:      #ffffff;
            --c-border:    #e2e8f0;
            --c-text:      #1e293b;
            --c-muted:     #64748b;
            --c-subtle:    #94a3b8;

            /* Sidebar */
            --sb-bg:   #0f172a;
            --sb-w:    258px;
            --sb-cw:   70px;

            /* Navbar */
            --nb-h:    60px;

            /* Misc */
            --radius:  8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.1), 0 4px 8px rgba(0,0,0,0.06);
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --transition: 0.2s var(--ease);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--c-bg);
            color: var(--c-text);
            display: flex;
            height: 100vh;
            overflow: hidden;
            font-size: 13px;
            line-height: 1.5;
        }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        #sidebar {
            width: var(--sb-w);
            background: var(--sb-bg);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: width 0.3s var(--ease);
            overflow: hidden;
            z-index: 100;
        }

        #sidebar.collapsed { width: var(--sb-cw); }

        /* ══════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════ */
        .main-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }

        .navbar {
            height: var(--nb-h);
            background: var(--c-card);
            border-bottom: 1px solid var(--c-border);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            flex-shrink: 0;
            box-shadow: var(--shadow-sm);
            z-index: 50;
        }

        .hamburger {
            padding: 7px;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: var(--radius);
            color: var(--c-muted);
            display: flex;
            align-items: center;
            transition: all var(--transition);
            flex-shrink: 0;
        }
        .hamburger:hover { background: var(--c-bg); color: var(--c-text); }
        .hamburger svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }

        /* Breadcrumb */
        .nb-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--c-muted);
            flex: 1;
            min-width: 0;
        }
        .nb-breadcrumb .bc-sep { color: var(--c-subtle); }
        .nb-breadcrumb .bc-current { color: var(--c-text); font-weight: 600; }
        .nb-breadcrumb a { color: var(--c-muted); text-decoration: none; white-space: nowrap; }
        .nb-breadcrumb a:hover { color: var(--c-accent); }

        /* Navbar Right */
        .nb-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }

        .notif-btn {
            position: relative;
            padding: 8px;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: var(--radius);
            color: var(--c-muted);
            transition: all var(--transition);
        }
        .notif-btn:hover { background: var(--c-bg); color: var(--c-text); }
        .notif-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; display: block; }
        .notif-badge {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: var(--c-danger);
            border-radius: 50%;
            border: 2px solid white;
            animation: pulseRed 2s ease-in-out infinite;
        }
        @keyframes pulseRed {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
            50%       { box-shadow: 0 0 0 4px rgba(239,68,68,0); }
        }

        /* User Menu */
        .user-menu { position: relative; }
        .user-trigger {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 8px 5px 5px;
            border-radius: var(--radius);
            border: none; background: none; cursor: pointer;
            transition: all var(--transition);
        }
        .user-trigger:hover { background: var(--c-bg); }
        .user-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 700;
            flex-shrink: 0;
        }
        .user-name { font-size: 12px; font-weight: 600; color: var(--c-text); white-space: nowrap; text-align: left; }
        .user-role { font-size: 10px; color: var(--c-muted); white-space: nowrap; }
        .user-chevron { width: 12px; height: 12px; stroke: var(--c-subtle); fill: none; stroke-width: 2.5; transition: transform 0.2s; }
        .user-menu.open .user-chevron { transform: rotate(180deg); }

        .dropdown {
            position: absolute; right: 0; top: calc(100% + 8px);
            background: white; border: 1px solid var(--c-border);
            border-radius: var(--radius-lg); min-width: 200px;
            box-shadow: var(--shadow-lg);
            opacity: 0; visibility: hidden;
            transform: translateY(-6px) scale(0.97);
            transition: all 0.18s var(--ease);
            z-index: 200; overflow: hidden;
        }
        .user-menu.open .dropdown { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        .dd-header { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; }
        .dd-header strong { display: block; font-size: 12px; color: var(--c-text); font-weight: 600; }
        .dd-header span { font-size: 11px; color: var(--c-muted); }
        .dd-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px; color: #374151; font-size: 12px;
            text-decoration: none; transition: background var(--transition);
        }
        .dd-item:hover { background: #f8fafc; }
        .dd-item svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
        .dd-item.danger { color: var(--c-danger); }
        .dd-item.danger:hover { background: #fef2f2; }
        .dd-item.logout-form { padding: 0; }
        .dd-item.logout-form button {
            display: flex; align-items: center; gap: 10px;
            width: 100%; padding: 9px 16px;
            background: none; border: none; color: var(--c-danger);
            font-size: 12px; font-family: 'Inter', sans-serif;
            cursor: pointer; text-align: left; transition: background var(--transition);
        }
        .dd-item.logout-form button:hover { background: #fef2f2; }
        .dd-divider { height: 1px; background: #f1f5f9; margin: 2px 0; }

        /* ══════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════ */
        .main-content {
            flex: 1; overflow-y: auto; padding: 24px;
            animation: pageLoad 0.3s var(--ease);
        }
        @keyframes pageLoad {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .main-content::-webkit-scrollbar { width: 5px; }
        .main-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        /* ══════════════════════════════════════
           ERP LAYOUT CONTAINERS
        ══════════════════════════════════════ */
        .erp-container {
            background: var(--c-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--c-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .erp-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid var(--c-border);
            background: #fafbfc;
        }
        .erp-header h2, .erp-header h3 {
            font-size: 14px; font-weight: 700; color: var(--c-text); margin: 0;
        }
        .erp-header .erp-header-actions { display: flex; align-items: center; gap: 8px; }
        .erp-body { padding: 20px; }
        .erp-footer {
            padding: 12px 20px;
            border-top: 1px solid var(--c-border);
            background: #fafbfc;
            display: flex; align-items: center; justify-content: flex-end; gap: 8px;
        }

        /* ERP Box (card within body) */
        .erp-box {
            background: var(--c-card);
            border: 1px solid var(--c-border);
            border-radius: var(--radius);
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
        }
        .group-bar {
            background: #f8fafc;
            border-left: 3px solid var(--c-accent);
            padding: 8px 14px;
            margin-bottom: 12px;
            border-radius: 0 var(--radius) var(--radius) 0;
            font-size: 11px; font-weight: 700;
            color: var(--c-primary); text-transform: uppercase; letter-spacing: 0.05em;
        }

        /* ══════════════════════════════════════
           ERP TABLE SYSTEM
        ══════════════════════════════════════ */
        .erp-table-wrap {
            border: 1px solid var(--c-border);
            border-radius: var(--radius);
            overflow: hidden;
            overflow-x: auto;
        }
        .erp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .erp-table thead th {
            background: #f8fafc;
            color: var(--c-muted);
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 14px;
            border-bottom: 2px solid var(--c-border);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .erp-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background var(--transition);
        }
        .erp-table tbody tr:nth-child(even) { background: #fafbfc; }
        .erp-table tbody tr:hover { background: #eff6ff; cursor: pointer; }
        .erp-table tbody tr:last-child { border-bottom: none; }
        .erp-table tbody td {
            padding: 10px 14px;
            color: var(--c-text);
            vertical-align: middle;
        }
        .erp-table tfoot td {
            padding: 10px 14px;
            border-top: 2px solid var(--c-border);
            background: #f8fafc;
            font-weight: 700;
            font-size: 12px;
            color: var(--c-text);
        }

        /* Table alignment helpers */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .font-mono { font-variant-numeric: tabular-nums; }
        .font-bold { font-weight: 700; }

        /* ══════════════════════════════════════
           BADGE / STATUS
        ══════════════════════════════════════ */
        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 8px; border-radius: 20px;
            font-size: 10px; font-weight: 600;
            white-space: nowrap;
        }
        .badge-blue    { background: #eff6ff; color: #2563eb; }
        .badge-green   { background: #f0fdf4; color: #16a34a; }
        .badge-amber   { background: #fffbeb; color: #d97706; }
        .badge-red     { background: #fef2f2; color: #dc2626; }
        .badge-gray    { background: #f8fafc; color: var(--c-muted); }
        .badge-indigo  { background: #eef2ff; color: #4f46e5; }

        /* ══════════════════════════════════════
           BUTTON SYSTEM
        ══════════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 16px;
            border-radius: 6px; border: none; cursor: pointer;
            font-size: 12px; font-weight: 600;
            font-family: 'Inter', sans-serif;
            transition: all var(--transition);
            text-decoration: none; white-space: nowrap;
            box-shadow: var(--shadow-sm);
        }
        .btn svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2.5; flex-shrink: 0; }
        .btn:active { transform: scale(0.97); }

        .btn-primary { background: var(--c-accent); color: #fff; }
        .btn-primary:hover { background: #2563eb; box-shadow: 0 4px 12px rgba(59,130,246,0.35); }

        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,0.35); }

        .btn-danger { background: var(--c-danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239,68,68,0.35); }

        .btn-warning { background: var(--c-warning); color: #fff; }
        .btn-warning:hover { background: #d97706; }

        .btn-ghost {
            background: var(--c-card); color: var(--c-text);
            border: 1px solid var(--c-border);
            box-shadow: none;
        }
        .btn-ghost:hover { background: var(--c-bg); border-color: #cbd5e1; }

        .btn-dark { background: var(--c-primary); color: #fff; }
        .btn-dark:hover { background: #0f172a; }

        .btn-sm { padding: 5px 12px; font-size: 11px; }
        .btn-lg { padding: 10px 22px; font-size: 13px; }
        .btn-icon { padding: 7px; border-radius: 6px; }
        .btn[disabled], .btn:disabled { opacity: 0.55; pointer-events: none; }

        /* Old-style ERP toolbar buttons (backward compat) */
        .btn-tool {
            background: var(--c-card); border: 1px solid var(--c-border);
            border-radius: 5px; padding: 6px 12px;
            font-size: 11.5px; font-weight: 600; color: var(--c-text);
            cursor: pointer; transition: all var(--transition);
            display: inline-flex; align-items: center; gap: 5px;
            box-shadow: var(--shadow-sm);
            font-family: 'Inter', sans-serif;
        }
        .btn-tool:hover { background: var(--c-bg); border-color: #94a3b8; }
        .btn-tool svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
        .btn-save { background: var(--c-primary); color: #fff; border: none; }
        .btn-save:hover { background: #334155; color: #fff; }

        /* ══════════════════════════════════════
           FORM INPUTS
        ══════════════════════════════════════ */
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label {
            font-size: 10.5px; font-weight: 700;
            color: var(--c-muted); text-transform: uppercase; letter-spacing: 0.05em;
        }
        .erp-input {
            background: #fff; border: 1.5px solid var(--c-border);
            border-radius: 6px; padding: 7px 10px;
            font-size: 12.5px; font-family: 'Inter', sans-serif;
            color: var(--c-text);
            outline: none; transition: all var(--transition);
            width: 100%;
        }
        .erp-input:focus {
            border-color: var(--c-accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
        .erp-input:disabled { background: #f8fafc; color: var(--c-muted); }
        .erp-input::placeholder { color: var(--c-subtle); }
        select.erp-input { cursor: pointer; }
        textarea.erp-input { resize: vertical; }

        /* ══════════════════════════════════════
           ALERTS
        ══════════════════════════════════════ */
        .alert {
            padding: 11px 16px; border-radius: var(--radius);
            font-size: 12.5px; font-weight: 500;
            margin-bottom: 16px; border: 1px solid transparent;
        }
        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .alert-error   { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .alert-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }

        /* ══════════════════════════════════════
           TOAST NOTIFICATION
        ══════════════════════════════════════ */
        #toast-container {
            position: fixed; top: 20px; right: 20px;
            z-index: 9999; display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
        }
        .toast {
            display: flex; align-items: center; gap: 10px;
            padding: 13px 18px; border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            font-size: 12.5px; font-weight: 500;
            min-width: 280px; max-width: 380px;
            pointer-events: all;
            animation: toastIn 0.3s var(--ease);
            border: 1px solid transparent;
        }
        .toast.hiding { animation: toastOut 0.2s var(--ease) forwards; }
        .toast-success { background: #fff; border-color: var(--c-success); color: #166534; }
        .toast-success .toast-icon { color: var(--c-success); }
        .toast-error   { background: #fff; border-color: var(--c-danger); color: #991b1b; }
        .toast-error .toast-icon { color: var(--c-danger); }
        .toast-icon svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2.5; flex-shrink: 0; }
        .toast-msg { flex: 1; }
        @keyframes toastIn  { from { opacity: 0; transform: translateX(16px); }  to { opacity: 1; transform: translateX(0); } }
        @keyframes toastOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(16px); } }

        /* ══════════════════════════════════════
           LOADING OVERLAY
        ══════════════════════════════════════ */
        #loading-bar {
            position: fixed; top: 0; left: 0; right: 0;
            height: 3px; background: var(--c-accent);
            z-index: 9998; transform: scaleX(0); transform-origin: left;
            transition: transform 0.3s var(--ease);
        }
        #loading-bar.active { transform: scaleX(0.7); }
        #loading-bar.done   { transform: scaleX(1); opacity: 0; transition: transform 0.3s, opacity 0.3s 0.2s; }

        /* ══════════════════════════════════════
           STAT CARDS (Dashboard / Overview)
        ══════════════════════════════════════ */
        .stat-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--c-card);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
            border-left: 4px solid transparent;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-card.blue   { border-left-color: var(--c-accent); }
        .stat-card.green  { border-left-color: var(--c-success); }
        .stat-card.red    { border-left-color: var(--c-danger); }
        .stat-card.amber  { border-left-color: var(--c-warning); }
        .stat-card.indigo { border-left-color: #6366f1; }
        .stat-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 2; }
        .stat-icon.blue   { background: #eff6ff; color: var(--c-accent); }
        .stat-icon.green  { background: #f0fdf4; color: #16a34a; }
        .stat-icon.red    { background: #fef2f2; color: var(--c-danger); }
        .stat-icon.amber  { background: #fffbeb; color: var(--c-warning); }
        .stat-icon.indigo { background: #eef2ff; color: #6366f1; }
        .stat-label { font-size: 10.5px; font-weight: 700; color: var(--c-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--c-text); line-height: 1.1; letter-spacing: -0.04em; }
        .stat-sub   { font-size: 10.5px; color: var(--c-subtle); margin-top: 2px; }

        /* ══════════════════════════════════════
           EMPTY STATE
        ══════════════════════════════════════ */
        .empty-state {
            text-align: center; padding: 48px 24px;
            color: var(--c-muted);
        }
        .empty-state svg { width: 40px; height: 40px; stroke: var(--c-subtle); fill: none; stroke-width: 1.5; margin-bottom: 14px; }
        .empty-state p { font-size: 13px; }

        /* ══════════════════════════════════════
           MISC UTILITIES
        ══════════════════════════════════════ */
        .divider { height: 1px; background: var(--c-border); margin: 16px 0; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99; backdrop-filter: blur(2px); }
        .overlay.show { display: block; }

        /* ══════════════════════════════════════
           RIBBON TOOLBAR & WINDOW TITLE
        ══════════════════════════════════════ */
        .window-title-bar {
            background: #fff;
            border-bottom: 1px solid var(--c-border);
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--c-text);
            flex-shrink: 0;
        }

        .ribbon-toolbar {
            background: #fff;
            border-bottom: 1px solid var(--c-border);
            padding: 8px;
            display: flex;
            gap: 20px;
            overflow-x: auto;
            flex-shrink: 0;
            user-select: none;
        }
        .ribbon-toolbar::-webkit-scrollbar { height: 4px; }
        .ribbon-toolbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        .ribbon-group {
            display: flex;
            flex-direction: column;
            border-right: 1px solid #f1f5f9;
            padding-right: 15px;
            min-width: 60px;
        }
        .ribbon-group:last-child { border-right: none; }
        
        .ribbon-actions {
            display: flex;
            gap: 6px;
            flex: 1;
            align-items: center;
        }

        .ribbon-group-label {
            font-size: 10px;
            color: var(--c-subtle);
            text-align: center;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .ribbon-btn {
            background: transparent;
            border: 1px solid transparent;
            border-radius: var(--radius);
            padding: 6px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            cursor: pointer;
            transition: all var(--transition);
            color: var(--c-text);
            min-width: 50px;
        }
        .ribbon-btn:hover {
            background: #eff6ff;
            border-color: #dbeafe;
            color: var(--c-accent);
        }
        .ribbon-btn svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.5;
        }
        .ribbon-btn span {
            font-size: 11px;
            font-weight: 500;
            line-height: 1.1;
            text-align: center;
        }

        .ribbon-btn.small {
            padding: 3px 6px;
            flex-direction: row;
            gap: 6px;
            min-width: 0;
            width: 100%;
            justify-content: flex-start;
        }
        .ribbon-btn.small svg { width: 13px; height: 13px; stroke-width: 2; }
        .ribbon-btn.small span { font-size: 10.5px; }

        .ribbon-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2px;
        }

        .skin-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 4px;
            padding: 4px;
        }
        .skin-item {
            width: 14px;
            height: 14px;
            border-radius: 3px;
            cursor: pointer;
            transition: transform 0.1s;
        }
        .skin-item:hover { transform: scale(1.2); }

        /* ── Pager inside Ribbon or Title ── */
        .pager-btn {
            background: #fff;
            border: 1px solid var(--c-border);
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
            cursor: pointer;
            color: var(--c-muted);
            transition: all 0.2s;
        }
        .pager-btn:hover:not(:disabled) { background: #f8fafc; border-color: var(--c-subtle); color: var(--c-text); }
        .pager-btn:disabled { opacity: 0.4; cursor: not-allowed; }

        /* Mobile */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed; top: 0; left: 0; bottom: 0;
                transform: translateX(-100%);
                transition: transform 0.3s var(--ease);
                width: var(--sb-w) !important;
            }
            #sidebar.mobile-open { transform: translateX(0); }
            .main-content { padding: 16px; }
        }
    </style>
    @stack('styles')
</head>

<body>

<div id="loading-bar"></div>
<div id="toast-container"></div>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside id="sidebar">
    <x-sidebar />
</aside>

<!-- MAIN -->
<div class="main-wrap">
    @include('layouts.navbar')
    <div class="main-content">
        {{-- Session flash as toast --}}
        @if(session('success'))
            <script>document.addEventListener('DOMContentLoaded',()=>showToast('success',@json(session('success'))));</script>
        @endif
        @if(session('error'))
            <script>document.addEventListener('DOMContentLoaded',()=>showToast('error',@json(session('error'))));</script>
        @endif

        @yield('content')
    </div>
</div>

<script>
    /* ── Sidebar ── */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const MOBILE = () => window.innerWidth <= 768;

    document.getElementById('hamburger').addEventListener('click', () => {
        if (MOBILE()) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show');
        } else {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sb_collapsed', sidebar.classList.contains('collapsed'));
        }
    });
    if (!MOBILE() && localStorage.getItem('sb_collapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }

    function closeSidebar() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
    }

    /* ── Submenu ── */
    document.querySelectorAll('.menu-toggle').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (btn.tagName.toLowerCase() === 'button' || !btn.getAttribute('href') || btn.getAttribute('href') === '#') {
                e.preventDefault();
            }
            document.querySelectorAll('.menu').forEach(m => {
                if (m !== btn.parentElement) m.classList.remove('active');
            });
            btn.parentElement.classList.toggle('active');
        });
    });

    /* ── User Dropdown ── */
    document.getElementById('userTrigger')?.addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('userMenuEl').classList.toggle('open');
    });
    document.addEventListener('click', () => {
        document.getElementById('userMenuEl')?.classList.remove('open');
    });

    /* ── Collapse btn inside sidebar ── */
    document.getElementById('collapseSbBtn')?.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sb_collapsed', sidebar.classList.contains('collapsed'));
    });

    /* ── Loading bar on navigation ── */
    const loadingBar = document.getElementById('loading-bar');
    document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])').forEach(a => {
        a.addEventListener('click', () => {
            loadingBar.classList.add('active');
        });
    });
    document.querySelectorAll('form').forEach(f => {
        f.addEventListener('submit', () => {
            loadingBar.classList.add('active');
        });
    });

    /* ── Toast ── */
    function showToast(type, message) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const iconSvg = type === 'success'
          ? `<svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`
          : `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;

        toast.innerHTML = `<span class="toast-icon">${iconSvg}</span><span class="toast-msg">${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 250);
        }, 3500);
    }
    window.showToast = showToast;
</script>

@stack('scripts')
</body>
</html>