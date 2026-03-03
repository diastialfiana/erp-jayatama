@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
    <style>
        /* ── Welcome Banner ── */
        .welcome-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0f1b3d 0%, #1E3A8A 40%, #2563EB 70%, #4F46E5 100%);
            border-radius: 20px;
            padding: 30px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 26px;
            box-shadow: 0 8px 36px rgba(30, 58, 138, 0.35);
            animation: slideBanner 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideBanner {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* decorative blobs */
        .welcome-card::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -40px;
            width: 240px;
            height: 240px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            bottom: -100px;
            right: 160px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .welcome-text h2 {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.02em;
        }

        .welcome-text p {
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.82rem;
        }

        .welcome-role {
            flex-shrink: 0;
            z-index: 1;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #fff;
            padding: 8px 18px;
            border-radius: 40px;
            font-size: 0.78rem;
            font-weight: 600;
            backdrop-filter: blur(6px);
            white-space: nowrap;
        }

        /* ── Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }

        .stat-card {
            background: #fff;
            border-radius: 18px;
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #eef2ff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
            position: relative;
            overflow: hidden;
            animation: fadeCard 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.10s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.20s;
        }

        @keyframes fadeCard {
            from {
                opacity: 0;
                transform: translateY(14px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .stat-card:hover {
            transform: translateY(-4px) scale(1.015);
            box-shadow: 0 14px 36px rgba(0, 0, 0, 0.1);
        }

        /* large bg icon */
        .stat-card .bg-icon {
            position: absolute;
            right: -8px;
            bottom: -8px;
            opacity: 0.04;
            pointer-events: none;
        }

        .stat-card .bg-icon svg {
            width: 80px;
            height: 80px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.5;
        }

        .stat-icon-wrap {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon-wrap svg {
            width: 21px;
            height: 21px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .ic-blue {
            background: #eff6ff;
            color: #2563EB;
        }

        .ic-indigo {
            background: #eef2ff;
            color: #4F46E5;
        }

        .ic-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .ic-amber {
            background: #fffbeb;
            color: #d97706;
        }

        .stat-body {}

        .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.9rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .stat-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            margin-top: 3px;
        }

        /* ── Bottom Panels ── */
        .panels-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        @media(max-width: 860px) {
            .panels-row {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            border: 1px solid #eef2ff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .panel-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
        }

        .panel-action {
            font-size: 0.75rem;
            color: #2563EB;
            text-decoration: none;
            font-weight: 500;
        }

        .panel-action:hover {
            text-decoration: underline;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot-blue {
            background: #2563EB;
        }

        .dot-green {
            background: #16a34a;
        }

        .dot-amber {
            background: #f59e0b;
        }

        .dot-purple {
            background: #7c3aed;
        }

        .info-text {
            font-size: 0.83rem;
            color: #374151;
        }

        .info-badge {
            margin-left: auto;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-blue {
            background: #eff6ff;
            color: #2563EB;
        }

        .badge-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .badge-amber {
            background: #fffbeb;
            color: #d97706;
        }

        .badge-purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        @media (max-width: 560px) {
            .welcome-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 380px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Welcome --}}
    <div class="welcome-card">
        <div class="welcome-text">
            <h2>Selamat datang, {{ auth()->user()->nama_lengkap }}! 👋</h2>
            <p>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &bull; JES – Jayatama Enterprise System</p>
        </div>
        <span class="welcome-role">{{ auth()->user()->roles->pluck('name')->implode(', ') }}</span>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrap ic-blue">
                <svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div class="stat-body">
                <div class="stat-label">Total Pegawai</div>
                <div class="stat-value" data-target="{{ $totalPegawai }}">0</div>
                <div class="stat-sub">Terdaftar di sistem</div>
            </div>
            <div class="bg-icon ic-blue"><svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap ic-indigo">
                <svg viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </div>
            <div class="stat-body">
                <div class="stat-label">Total Divisi</div>
                <div class="stat-value" data-target="{{ $totalDivisi }}">0</div>
                <div class="stat-sub">Unit kerja aktif</div>
            </div>
            <div class="bg-icon ic-indigo"><svg viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap ic-green">
                <svg viewBox="0 0 24 24">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                </svg>
            </div>
            <div class="stat-body">
                <div class="stat-label">Total Jabatan</div>
                <div class="stat-value" data-target="{{ $totalJabatan }}">0</div>
                <div class="stat-sub">Level jabatan</div>
            </div>
            <div class="bg-icon ic-green"><svg viewBox="0 0 24 24">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                </svg></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap ic-amber">
                <svg viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <div class="stat-body">
                <div class="stat-label">User Aktif</div>
                <div class="stat-value" data-target="{{ $totalUserAktif }}">0</div>
                <div class="stat-sub">Akun aktif saat ini</div>
            </div>
            <div class="bg-icon ic-amber"><svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg></div>
        </div>
    </div>

    {{-- Panels --}}
    <div class="panels-row">
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Status Modul JES</span>
                <a href="#" class="panel-action">Selengkapnya</a>
            </div>
            <div class="info-row"><span class="dot dot-green"></span><span class="info-text">Autentikasi &amp;
                    Keamanan</span><span class="info-badge badge-green">Aktif</span></div>
            <div class="info-row"><span class="dot dot-blue"></span><span class="info-text">Master Data &amp;
                    User</span><span class="info-badge badge-blue">Development</span></div>
            <div class="info-row"><span class="dot dot-amber"></span><span class="info-text">Inventory &amp; GA</span><span
                    class="info-badge badge-amber">Coming Soon</span></div>
            <div class="info-row"><span class="dot dot-amber"></span><span class="info-text">Finance &amp;
                    Keuangan</span><span class="info-badge badge-amber">Coming Soon</span></div>
            <div class="info-row"><span class="dot dot-amber"></span><span class="info-text">Accounting</span><span
                    class="info-badge badge-amber">Coming Soon</span></div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Informasi Sistem</span>
            </div>
            <div class="info-row"><span class="dot dot-blue"></span><span class="info-text">Framework</span><span
                    class="info-badge badge-blue">Laravel {{ app()->version() }}</span></div>
            <div class="info-row"><span class="dot dot-green"></span><span class="info-text">Database</span><span
                    class="info-badge badge-green">MySQL</span></div>
            <div class="info-row"><span class="dot dot-green"></span><span class="info-text">Environment</span><span
                    class="info-badge badge-green">{{ ucfirst(app()->environment()) }}</span></div>
            <div class="info-row"><span class="dot dot-purple"></span><span class="info-text">PHP</span><span
                    class="info-badge badge-purple">{{ phpversion() }}</span></div>
            <div class="info-row"><span class="dot dot-green"></span><span class="info-text">Permission Engine</span><span
                    class="info-badge badge-green">Spatie v6</span></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.stat-value[data-target]').forEach(el => {
                const target = parseInt(el.dataset.target) || 0;
                if (!target) { el.textContent = '0'; return; }
                let start = 0;
                const end = target;
                const duration = 1000;
                const increment = Math.ceil(end / (duration / 16));
                const timer = setInterval(() => {
                    start = Math.min(start + increment, end);
                    el.textContent = start.toLocaleString('id');
                    if (start >= end) clearInterval(timer);
                }, 16);
            });
        });
    </script>
@endsection