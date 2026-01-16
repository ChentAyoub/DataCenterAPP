<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if ($user->role === 'student') {
        return redirect()->route('reservations.my_list');
    }

    if ($user->role === 'admin') {

        $role_counts = [
            'student' => \App\Models\User::where('role', 'student')->count(),
            'manager' => \App\Models\User::where('role', 'manager')->count(),
            'admin'   => \App\Models\User::where('role', 'admin')->count(),
        ];
        $resource_stats = [
            'available'   => \App\Models\Resource::where('state', 'available')->count(),
            'maintenance' => \App\Models\Resource::where('state', 'maintenance')->count(),
        ];
        $recent_users = \App\Models\User::orderBy('created_at', 'desc')->take(5)->get();
        return view('dashboard.admin', compact('role_counts', 'resource_stats', 'recent_users'));
    }
    if ($user->role === 'manager') {
        $pending_reservations = \App\Models\Reservation::where('status', 'pending')
            ->with(['user', 'resource'])
            ->orderBy('created_at', 'asc')
            ->get();

        $broken_resources = \App\Models\Resource::where('state', 'maintenance')->get();
        
        $active_count = \App\Models\Reservation::where('status', 'approved')
            ->where('end_time', '>', now())
            ->count();

        return view('dashboard.manager', compact('pending_reservations', 'broken_resources', 'active_count'));
    }
}
}