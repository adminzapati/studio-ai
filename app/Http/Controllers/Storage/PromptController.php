<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prompts = \App\Models\SavedPrompt::where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('storage.prompts.index', compact('prompts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('storage.prompts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prompt' => 'required|string',
            'category' => 'nullable|string|max:50',
            'is_favorite' => 'boolean',
        ]);

        $request->user()->savedPrompts()->create($validated);

        return redirect()->route('prompts.index')->with('success', 'Prompt created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prompt = \App\Models\SavedPrompt::where('user_id', auth()->id())->findOrFail($id);
        return view('storage.prompts.show', compact('prompt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prompt = \App\Models\SavedPrompt::where('user_id', auth()->id())->findOrFail($id);
        return view('storage.prompts.edit', compact('prompt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prompt = \App\Models\SavedPrompt::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prompt' => 'required|string',
            'category' => 'nullable|string|max:50',
            'is_favorite' => 'boolean',
        ]);

        $prompt->update($validated);

        return redirect()->route('prompts.index')->with('success', 'Prompt updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prompt = \App\Models\SavedPrompt::where('user_id', auth()->id())->findOrFail($id);
        $prompt->delete();

        return redirect()->route('prompts.index')->with('success', 'Prompt deleted successfully.');
    }
}
