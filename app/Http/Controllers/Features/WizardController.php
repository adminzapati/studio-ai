<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WizardController extends Controller
{
    public function index()
    {
        $options = \App\Models\PromptOption::all();
        
        // Group by step for cleaner initial load, but we can also just pass the whole collection
        // JS will likely want grouped data.
        $steps = $options->groupBy('step');

        return view('features.wizard.index', compact('steps'));
    }
}
