@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h2 style="margin-top: 0;">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
        <p>Ini adalah halaman Dashboard utama ERP Jayatama.</p>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
            <div style="background: #17a2b8; color: white; padding: 20px; border-radius: 8px;">
                <h3>Role Anda</h3>
                <p>{{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
            </div>
            <div style="background: #28a745; color: white; padding: 20px; border-radius: 8px;">
                <h3>Status Akun</h3>
                <p>Aktif</p>
            </div>
        </div>
    </div>
@endsection