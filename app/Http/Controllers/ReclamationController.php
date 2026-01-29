<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:120',
            'category' => 'nullable|string|max:60',
            'priority' => 'required|in:low,normal,high,urgent',
            'message' => 'required|string|max:1000',
        ]);

        Reclamation::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return back()->with('success', 'Reclamation submitted successfully.');
    }
}
