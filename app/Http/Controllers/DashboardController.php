<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\User;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'internal_user') {
            return redirect()->route('reservations.my_list');
        }

        if ($user->role === 'admin') {
            $role_counts = [
                'internal users' => User::where('role', 'internal_user')->count(),
                'managers' => User::where('role', 'manager')->count(),
                'admins'   => User::where('role', 'admin')->count(),
            ];
            $resource_stats = [
                'available'   => Resource::where('state', 'available')->count(),
                'maintenance' => Resource::where('state', 'maintenance')->count(),
            ];
            $recent_users = User::orderBy('created_at', 'desc')->take(5)->get();
            $pending_reservations = Reservation::where('status', 'pending')
                ->with(['user', 'resource'])
                ->orderBy('created_at', 'asc')
                ->get();

            return view('dashboard.admin', compact('role_counts', 'resource_stats', 'recent_users', 'pending_reservations'));
        }
        
        if ($user->role === 'manager') {
            $pending_reservations = Reservation::where('status', 'pending')
                ->with(['user', 'resource'])
                ->orderBy('created_at', 'asc')
                ->get();

            $broken_resources = Resource::where('state', 'maintenance')->get();
            
            $active_count = Reservation::where('status', 'approved')
                ->where('end_time', '>', now())
                ->count();

            return view('dashboard.manager', compact('pending_reservations', 'broken_resources', 'active_count'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // 1. Check Permissions
        if (Auth::user()->role !== 'manager' && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $reservation = Reservation::findOrFail($id);
        $status = $request->input('status');

        // --- DEBUG: STOP AND SHOW ME THE DATA ---
        // kenIf the screen does NOT go black with this text, the form is bro.
        // If it DOES go black, the Controller is connected perfectly.
        // dd('Controller Reached! Status is: ' . $status); 
        // ----------------------------------------

        $reservation->status = $status;
        $reservation->save();

        if ($status === 'approved') {
            
            // Create the notification
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => 'Good news! Your reservation for "' . $reservation->resource->name . '" was APPROVED.',
                'type'    => 'success',
                'is_read' => false,
            ]);

        } elseif ($status === 'rejected') {
            
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => 'Your reservation for "' . $reservation->resource->name . '" was declined.',
                'type'    => 'info',
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Reservation updated and student notified!');
    }
}