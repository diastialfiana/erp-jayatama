@extends('layouts.app')

@section('title', 'Administrator')

@section('content')
<div class="erp-container">
    <div class="erp-header">
        <div>
            <h2>Administrator</h2>
            <div style="font-size:11px; color:var(--c-muted); margin-top:2px;">Kelola pengaturan sistem, pengguna, dan akses</div>
        </div>
    </div>

    <div class="erp-body" style="min-height: 320px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:16px;">

        <div style="width:64px; height:64px; background:linear-gradient(135deg,#3b82f6,#4f46e5); border-radius:16px; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 24px rgba(79,70,229,0.3);">
            <svg viewBox="0 0 24 24" style="width:30px;height:30px;stroke:white;fill:none;stroke-width:2;">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>

        <div style="text-align:center;">
            <div style="font-size:16px; font-weight:700; color:var(--c-text); margin-bottom:6px;">Modul Administrator</div>
            <div style="font-size:12.5px; color:var(--c-muted); max-width:360px; line-height:1.7;">
                Gunakan menu di sebelah kiri untuk mengelola <strong>Visibilitas Menu</strong>, <strong>Manajemen User</strong>, dan pengaturan sistem lainnya.
            </div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:8px;">
            <a href="{{ route('administrator.menu-visibility') }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Menu Visibility
            </a>
            <a href="{{ route('administrator.users.index') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Manajemen User
            </a>
        </div>

    </div>
</div>
@endsection
