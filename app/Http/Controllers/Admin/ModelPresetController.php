<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelPreset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelPresetController extends Controller
{
    public function index()
    {
        $presets = ModelPreset::latest()->paginate(10);
        return view('admin.model-presets.index', compact('presets'));
    }

    public function create()
    {
        return view('admin.model-presets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:male,female,unisex',
            'ethnicity' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
        ]);

        $path = $request->file('image')->store('model-presets', 'public');

        ModelPreset::create([
            'gender' => $request->gender,
            'ethnicity' => $request->ethnicity,
            'image_path' => $path,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.model-presets.index')->with('success', 'Model preset created.');
    }

    public function edit(ModelPreset $modelPreset)
    {
        return view('admin.model-presets.edit', compact('modelPreset'));
    }

    public function update(Request $request, ModelPreset $modelPreset)
    {
        $request->validate([
            'gender' => 'required|in:male,female,unisex',
            'ethnicity' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($modelPreset->image_path);
            $modelPreset->image_path = $request->file('image')->store('model-presets', 'public');
        }

        $modelPreset->gender = $request->gender;
        $modelPreset->ethnicity = $request->ethnicity;
        $modelPreset->is_active = $request->boolean('is_active');
        $modelPreset->save();

        return redirect()->route('admin.model-presets.index')->with('success', 'Model preset updated.');
    }

    public function destroy(ModelPreset $modelPreset)
    {
        Storage::disk('public')->delete($modelPreset->image_path);
        $modelPreset->delete();
        return redirect()->route('admin.model-presets.index')->with('success', 'Model preset deleted.');
    }
}
