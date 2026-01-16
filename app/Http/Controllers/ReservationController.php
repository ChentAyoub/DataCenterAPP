<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();


        $request->validate([
            'resource_id' => 'required|exists:resources,id',
        ]);

        Reservation::create([
            'user_id'     => $user->id,
            'resource_id' => $request->resource_id,
            'start_time'  => $request->start_time ?? now(),
            'end_time'    => $request->end_time ?? now()->addDays(7),
            'status'      => 'pending',
            'justification' => 'Booked via Home Page',
        ]);

        return back()->with('success', 'Reservation request sent successfully!');
    }

    //TODO:UPDATE RESERVATION STATUS (For Managers/Admins)
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role === 'internal_user') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->status = $request->status;
        $reservation->save();

        return back()->with('success', 'Reservation marked as ' . $request->status);
    }

    public function myReservations(Request $request)
    {
        $query = Reservation::where('user_id', auth()->id())->with('resource');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resource', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $reservations = $query->orderBy('created_at', 'desc')->get();
        return view('reservations.my_list', compact('reservations'));

    }


   public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->user_id != Auth::id() && Auth::user()->role === 'student') {
            abort(403, 'Unauthorized action.');
        }
        $reservation->delete();
        return back()->with('success', 'Reservation cancelled successfully.');
    }
}