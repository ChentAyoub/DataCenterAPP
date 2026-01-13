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

        // 1. If user is ADMIN: Show EVERYTHING
        if ($user->role === 'admin') {
            $stats = [
                'users' => User::count(),
                'resources' => Resource::count(),
                'reservations' => Reservation::count(),
            ];
            return view('dashboard.admin', compact('stats'));
        }

        // 2. If user is MANAGER: Show resources they manage & pending requests
        if ($user->role === 'manager') {
            $myResources = Resource::where('manager_id', $user->id)->get();
            // Get reservations for MY resources only
            $pendingReservations = Reservation::whereIn('resource_id', $myResources->pluck('id'))
                                            ->where('status', 'pending')
                                            ->with('user', 'resource')
                                            ->get();
            
            return view('dashboard.manager', compact('myResources', 'pendingReservations'));
        }

        // 3. If user is user : Show their own reservations
        if ($user->role === 'internal_user') {
            $myReservations = Reservation::where('user_id', $user->id)
                                         ->with('resource')
                                         ->orderBy('created_at', 'desc')
                                         ->get();

            return view('dashboard.user', compact('myReservations'));
        }

        // Default fallback (should not reach here)
        return redirect('/');
    }
}