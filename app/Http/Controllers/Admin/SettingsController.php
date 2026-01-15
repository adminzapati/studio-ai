<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'gemini_api_key' => Setting::get('gemini_api_key', ''),
            'fal_api_key' => Setting::get('fal_api_key', ''),
            'products_virtual_dev_mode' => Setting::get('products_virtual_dev_mode', 'false') === 'true',
            'products_virtual_daily_limit' => Setting::get('products_virtual_daily_limit', '10'),
            'products_virtual_total_limit' => Setting::get('products_virtual_total_limit', '100'),
        ];
        
        $tempStats = $this->calculateProductsVirtualTempSize();

        return view('admin.settings.index', compact('settings', 'tempStats'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'gemini_api_key' => 'nullable|string',
            'fal_api_key' => 'nullable|string',
            'products_virtual_dev_mode' => 'sometimes|boolean',
            'products_virtual_daily_limit' => 'nullable|integer|min:1',
            'products_virtual_total_limit' => 'nullable|integer|min:1',
        ]);

        // Handle boolean dev mode setting
        Setting::set('products_virtual_dev_mode', $request->has('products_virtual_dev_mode') ? 'true' : 'false', 'system');

        // Handle other settings
        $stringSettings = ['gemini_api_key', 'fal_api_key', 'products_virtual_daily_limit', 'products_virtual_total_limit'];
        foreach ($stringSettings as $key) {
            if (isset($validated[$key]) && $validated[$key] !== null && $validated[$key] !== '') {
                Setting::set($key, (string) $validated[$key], 'system');
            }
        }

        return back()->with('success', 'Settings updated successfully.');
        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Clear Products Virtual Temp Folder
     */
    public function clearProductsVirtualTemp()
    {
        $path = 'temp/products-virtual';
        
        if (Storage::disk('public')->exists($path)) {
            // Delete all files
            $files = Storage::disk('public')->allFiles($path);
            Storage::disk('public')->delete($files);
            
            // Or delete directory and recreate needed subfolders?
            // Current structure: temp/products-virtual/inputs/products, temp/products-virtual/inputs/models
            // We should clear everything inside temp/products-virtual
            
            // Delete directory contents recursively
            $all = Storage::disk('public')->allFiles($path);
             Storage::disk('public')->delete($all);
             
             // Delete inputs folder if exists to clear deeper structure?
             // Actually, just deleting files is safer, folders can stay.
             // But if we want to clear 'inputs' subfolder too...
             Storage::disk('public')->deleteDirectory($path);
             Storage::disk('public')->makeDirectory($path);
             Storage::disk('public')->makeDirectory($path . '/inputs/products');
             Storage::disk('public')->makeDirectory($path . '/inputs/models');
        }

        return back()->with('success', 'Temporary files cleared successfully.');
    }

    /**
     * Calculate size of temp folder
     */
    protected function calculateProductsVirtualTempSize()
    {
        $path = 'temp/products-virtual';
        $size = 0;
        $count = 0;

        if (Storage::disk('public')->exists($path)) {
            // Get all files recursively
            $files = Storage::disk('public')->allFiles($path);
            $count = count($files);
            
            foreach ($files as $file) {
                $size += Storage::disk('public')->size($file);
            }
        }

        // Format size
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        $formattedSize = number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];

        return [
            'size' => $formattedSize,
            'count' => $count
        ];
    }
}
