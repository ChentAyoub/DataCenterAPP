<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function toggleMaintenance($id)
    {
        // Security Check
        if (Auth::user()->role !== 'manager') {
            abort(403, 'Only Managers can flag resources.');
        }

        $resource = Resource::findOrFail($id);

        if ($resource->state === 'available') {
            $resource->state = 'maintenance';
            $msg = 'flagged as BROKEN ⚠️';
        } else {
            $resource->state = 'available';
            $msg = 'marked as FIXED ✅';
        }

        $resource->save();

        return back()->with('success', 'Resource ' . $msg);
    }

    public function destroy($id)
    {

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only Admins can delete resources.');
        }

        Resource::destroy($id);

        return back()->with('success', 'Resource deleted permanently.');
    }

    public function show($id)
    {
        $resource = Resource::findOrFail($id);
        return view('resources.show', compact('resource'));
    }

}
