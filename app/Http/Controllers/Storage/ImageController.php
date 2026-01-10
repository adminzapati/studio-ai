<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = \App\Models\ImageLibrary::where('user_id', auth()->id())
            ->latest()
            ->paginate(12);
        
        return view('storage.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('storage.images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'type' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('user-uploads', 'public');

            $request->user()->images()->create([
                'path' => $path,
                'type' => $request->input('type', 'upload'),
                'tags' => $request->input('tags'),
            ]);

            return redirect()->route('images.index')->with('success', 'Image uploaded successfully.');
        }

        return back()->withErrors(['image' => 'Image upload failed.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $image = \App\Models\ImageLibrary::where('user_id', auth()->id())->findOrFail($id);
        return view('storage.images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not implemented (Update tags planned later)
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Not implemented
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = \App\Models\ImageLibrary::where('user_id', auth()->id())->findOrFail($id);
        
        // Delete file from storage
        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->path);
        
        $image->delete();

        return redirect()->route('images.index')->with('success', 'Image deleted successfully.');
    }
}
