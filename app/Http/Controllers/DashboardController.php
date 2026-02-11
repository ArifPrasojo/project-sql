<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                $logs = ActivityLog::with('user')->latest()->take(40)->get();
                return view('admin.admin', compact('user', 'logs'));
                
            case 'dosen':
                return view('dosen.dosen', compact('user'));
            case 'mahasiswa':
            default:
                return view('mahasiswa.mahasiswa', compact('user'));
        }
    }
}