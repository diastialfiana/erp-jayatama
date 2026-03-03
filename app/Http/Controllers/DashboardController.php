<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Jabatan;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalPegawai' => User::count(),
            'totalDivisi' => Divisi::count(),
            'totalJabatan' => Jabatan::count(),
            'totalUserAktif' => User::where('is_active', true)->count(),
        ];
        return view('dashboard.index', $data);
    }
}
