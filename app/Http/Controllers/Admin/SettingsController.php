<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'gemini_api_key' => Setting::get('gemini_api_key', ''),
            'fal_api_key' => Setting::get('fal_api_key', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'gemini_api_key' => 'nullable|string',
            'fal_api_key' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null && $value !== '') {
                Setting::set($key, $value, 'system');
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
