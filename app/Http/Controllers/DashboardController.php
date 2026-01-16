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

        if ($user->role === 'student' || $user->role === 'internal_user') {
            return redirect()->route('reservations.my_list');
        }

        if ($user->role === 'manager') {
            $pending_count = Reservation::where('status', 'pending')->count();
            $active_count = Reservation::where('status', 'approved')->count();
            $total_resources = Resource::count();

            $recent_reservations = Reservation::with(['user', 'resource'])
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();
            
            return view('dashboard.manager', compact(
                'pending_count', 
                'active_count', 
                'total_resources',
                'recent_reservations'
            ));
        }
        if ($user->role === 'admin') {
            return view('dashboard.admin');
        }
        return redirect('/');
    }
}