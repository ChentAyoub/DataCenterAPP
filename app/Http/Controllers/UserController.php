<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

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