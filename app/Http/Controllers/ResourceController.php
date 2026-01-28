<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::query();
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('specifications', 'like', '%' . $request->search . '%');
        }
        $resources = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::all();
        return view('catalogue', compact('resources', 'categories'));
    }

    public function show($id)
    {
        $resource = Resource::findOrFail($id);
        return view('resources.show', compact('resource'));
    }

    public function manage()
    {
        if (Auth::user()->role === 'internal_user') abort(403, 'Unauthorized.');
        $resources = Resource::with('category')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('resources.manage', compact('resources'));
    }

    public function create()
    {
        if (Auth::user()->role === 'internal_user') abort(403);
        
        $categories = Category::all();
        return view('resources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'internal_user') abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'specifications' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('resources', 'public');
        }

        $validated['manager_id'] = Auth::id();
        $validated['state'] = 'available';

        Resource::create($validated);

        return redirect()->route('resources.manage')->with('success', 'Item created successfully!');
    }

    public function edit($id)
    {
        if (Auth::user()->role === 'internal_user') abort(403);

        $resource = Resource::findOrFail($id);
        $categories = Category::all();
        
        return view('resources.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'internal_user') abort(403);

        $resource = Resource::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'specifications' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($resource->image) {
                Storage::disk('public')->delete($resource->image);
            }
            $validated['image'] = $request->file('image')->store('resources', 'public');
        }

        $resource->update($validated);

        return redirect()->route('resources.manage')->with('success', 'Item updated successfully!');
    }

    public function toggleMaintenance($id)
    {
        if (Auth::user()->role === 'internal_user') abort(403);

        $resource = Resource::findOrFail($id);

        if ($resource->state === 'available') {
            $resource->state = 'maintenance';
            $msg = 'flagged as BROKEN';
        } else {
            $resource->state = 'available';
            $msg = 'marked as FIXED';
        }

        $resource->save();
        return back()->with('success', 'Resource ' . $msg);
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'internal_user') abort(403);

        $resource = Resource::findOrFail($id);
        if ($resource->image) {
            Storage::disk('public')->delete($resource->image);
        }

        $resource->delete();

        return back()->with('success', 'Resource deleted permanently.');
    }
}