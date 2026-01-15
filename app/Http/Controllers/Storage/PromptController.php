<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\SavedPrompt::query();

        // Text Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('prompt', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        // Favorite Filter
        if ($request->has('favorites') && $request->input('favorites') == '1') {
            $query->where('is_favorite', true);
        }

        // Method Filter
        if ($request->filled('method') && $request->input('method') !== 'all') {
            $query->where('method', $request->input('method'));
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'az':
                $query->orderBy('name', 'asc');
                break;
            case 'za':
                $query->orderBy('name', 'desc');
                break;
            default: // newest
                $query->latest();
                break;
        }

        $prompts = $query->paginate(12)->withQueryString();
        $categories = \App\Models\SavedPrompt::distinct()->pluck('category')->filter()->values();

        if ($request->wantsJson()) {
            return response()->json([
                'prompts' => $prompts,
                'categories' => $categories
            ]);
        }

        return view('storage.prompts.index', compact('prompts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('storage.prompts.create');
    }

    /**
     * Generate a prompt using AI.
     */
    public function generate(Request $request, \App\Domain\UseCases\Features\GeneratePromptUseCase $generatePromptUseCase)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:image,wizard,manual',
                'data' => 'required|array',
            ]);

            $generatedPrompt = $generatePromptUseCase->execute($validated['type'], $validated['data']);

            return response()->json([
                'success' => true,
                'prompt' => $generatedPrompt,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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
            'image_base64' => 'nullable|string', // Hidden input from frontend
            'method' => 'nullable|string|in:manual,image,wizard',
        ]);

        $imagePath = null;
        if (!empty($validated['image_base64'])) {
            try {
                // Determine image type (jpeg/png) from base64 header if possible, or default to jpg
                // Format usually: data:image/png;base64,.....
                $imageParts = explode(";base64,", $validated['image_base64']);
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'jpg';
                $imageBase64 = base64_decode($imageParts[1] ?? $validated['image_base64']);
                
                $fileName = 'prompt-images/' . uniqid() . '.' . $imageType;
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);
                $imagePath = $fileName;

                // Also save to Image Library
                \App\Models\ImageLibrary::create([
                    'user_id' => auth()->id(),
                    'path' => $fileName,
                    'type' => 'prompt_reference',
                    'tags' => ['prompt', $validated['name']],
                ]);

            } catch (\Exception $e) {
                // Log error but continue saving prompt without image
                \App\Core\Logging\AppLogger::error('Failed to save prompt image', ['error' => $e->getMessage()]);
            }
        }

        $request->user()->savedPrompts()->create([
            'name' => $validated['name'],
            'prompt' => $validated['prompt'],
            'category' => $validated['category'],
            'is_favorite' => $request->boolean('is_favorite'),
            'image_path' => $imagePath,
            'method' => $validated['method'] ?? 'manual',
        ]);

        return redirect()->route('storage.prompts.index')->with('success', 'Prompt saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prompt = \App\Models\SavedPrompt::findOrFail($id);
        return view('storage.prompts.show', compact('prompt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prompt = \App\Models\SavedPrompt::findOrFail($id);
        
        // Check permission: Owner or Admin
        if (auth()->id() !== $prompt->user_id && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Reuse create view but pass prompt data
        return view('storage.prompts.create', compact('prompt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prompt = \App\Models\SavedPrompt::findOrFail($id);

        // Check permission: Owner or Admin
        if (auth()->id() !== $prompt->user_id && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prompt' => 'required|string',
            'category' => 'nullable|string|max:50',
            'is_favorite' => 'boolean',
            'image_base64' => 'nullable|string',
            'method' => 'nullable|string|in:manual,image,wizard',
        ]);

        $data = [
            'name' => $validated['name'],
            'prompt' => $validated['prompt'],
            'category' => $validated['category'],
            'is_favorite' => $request->boolean('is_favorite'),
            'method' => $validated['method'] ?? $prompt->method,
        ];

        // Handle Image Update
        if (!empty($validated['image_base64'])) {
             try {
                $imageParts = explode(";base64,", $validated['image_base64']);
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'jpg';
                $imageBase64 = base64_decode($imageParts[1] ?? $validated['image_base64']);
                
                $fileName = 'prompt-images/' . uniqid() . '.' . $imageType;
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);
                
                // Delete old image if exists
                if ($prompt->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($prompt->image_path);
                }
                
                $data['image_path'] = $fileName;
            } catch (\Exception $e) {
                \App\Core\Logging\AppLogger::error('Failed to update prompt image', ['error' => $e->getMessage()]);
            }
        }

        $prompt->update($data);

        return redirect()->route('storage.prompts.index')->with('success', 'Prompt updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prompt = \App\Models\SavedPrompt::findOrFail($id);

        // Check permission: Owner or Admin
        if (auth()->id() !== $prompt->user_id && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($prompt->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($prompt->image_path);
            
            // Also remove from Image Library to prevent orphans
            \App\Models\ImageLibrary::where('path', $prompt->image_path)->delete();
        }
        
        $prompt->delete();

        return redirect()->route('storage.prompts.index')->with('success', 'Prompt deleted successfully.');
    }

    /**
     * Duplicate the specified resource.
     */
    public function duplicate(string $id)
    {
        $original = \App\Models\SavedPrompt::findOrFail($id);
        
        $newPrompt = $original->replicate();
        $newPrompt->name = $original->name . ' (Copy)';
        $newPrompt->user_id = auth()->id(); // Assign to current user
        $newPrompt->created_at = now();
        $newPrompt->updated_at = now();
        
        // Handle image duplication
        if ($original->image_path) {
            try {
                $ext = pathinfo($original->image_path, PATHINFO_EXTENSION);
                $newPath = 'prompt-images/' . uniqid() . '.' . $ext;
                \Illuminate\Support\Facades\Storage::disk('public')->copy($original->image_path, $newPath);
                $newPrompt->image_path = $newPath;
                
                // Also save entry to Image Library for the new owner
                \App\Models\ImageLibrary::create([
                    'user_id' => auth()->id(),
                    'path' => $newPath,
                    'type' => 'prompt_reference',
                    'tags' => ['prompt', $newPrompt->name],
                ]);
            } catch (\Exception $e) {
                // If copy fails, just proceed without image
            }
        }
        
        $newPrompt->save();

        return redirect()->route('storage.prompts.index')->with('success', 'Prompt duplicated successfully.');
    }
    /**
     * Toggle favorite status.
     */
    public function toggleFavorite(string $id)
    {
        $prompt = \App\Models\SavedPrompt::findOrFail($id);
        $prompt->update(['is_favorite' => !$prompt->is_favorite]);
        
        return back()->with('success', $prompt->is_favorite ? 'Added to favorites.' : 'Removed from favorites.');
    }
}
