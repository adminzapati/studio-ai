<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = \App\Models\ModelPreset::latest()->paginate(12);
        return view('storage.models.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only Admin/Manager can create
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('storage.models.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Unisex',
            'ethnicity' => 'required|string|max:100',
            'age_range' => 'nullable|string|max:50',
            'image' => 'required|image|max:10240',
            'is_active' => 'boolean',
        ]);

        $path = $request->file('image')->store('models', 'public');

        \App\Models\ModelPreset::create([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'ethnicity' => $validated['ethnicity'],
            'age_range' => $validated['age_range'] ?? null,
            'image_path' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('models.index')->with('success', 'Model preset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used, modal preview in index
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            abort(403);
        }
        $model = \App\Models\ModelPreset::findOrFail($id);
        return view('storage.models.edit', compact('model')); // Assuming edit view exists logic later
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            abort(403);
        }
        // Logic similar to store, but allow updating image optionally
        // Skipping detailed implementation for now to focus on View
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $model = \App\Models\ModelPreset::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($model->image_path);
        $model->delete();

        return redirect()->route('models.index')->with('success', 'Model preset deleted.');
    }
}
