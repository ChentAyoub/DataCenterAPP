<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification; // <--- IMPORTANT: Needed for alerts
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function approve($id)
    {
        
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();
        Notification::create([
            'user_id' => $user->id,
            'message' => 'Great news! Your account has been APPROVED. You can now reserve resources.',
            'type'    => 'success',
            'is_read' => false,
        ]);

        return back()->with('success', 'User approved successfully.');
    }

    public function reject($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User request rejected.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot delete yourself!');
        }

        User::destroy($id);
        return back()->with('success', 'User deleted successfully.');
    }
}