<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromptOption;
use Illuminate\Http\Request;

class WizardOptionController extends Controller
{
    public function index()
    {
        $options = PromptOption::orderBy('step')->orderBy('category')->get();
        return view('admin.wizard-options.index', compact('options'));
    }

    public function create()
    {
        return view('admin.wizard-options.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'step' => 'required|integer|min:1|max:5',
            'category' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        PromptOption::create($request->only(['step', 'category', 'value', 'icon']));
        return redirect()->route('admin.wizard-options.index')->with('success', 'Option created.');
    }

    public function edit(PromptOption $wizardOption)
    {
        return view('admin.wizard-options.edit', compact('wizardOption'));
    }

    public function update(Request $request, PromptOption $wizardOption)
    {
        $request->validate([
            'step' => 'required|integer|min:1|max:5',
            'category' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $wizardOption->update($request->only(['step', 'category', 'value', 'icon']));
        return redirect()->route('admin.wizard-options.index')->with('success', 'Option updated.');
    }

    public function destroy(PromptOption $wizardOption)
    {
        $wizardOption->delete();
        return redirect()->route('admin.wizard-options.index')->with('success', 'Option deleted.');
    }
}
