<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    /**
     * Display all images in storage
     */
    public function index(Request $request)
    {
        $disk = Storage::disk('public');
        $allFiles = $disk->allFiles();
        
        // Filter only images
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        $images = collect($allFiles)->filter(function ($file) use ($imageExtensions) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($extension, $imageExtensions);
        })->map(function ($file) use ($disk) {
            return [
                'path' => $file,
                'url' => $disk->url($file),
                'size' => $disk->size($file),
                'size_formatted' => $this->formatBytes($disk->size($file)),
                'modified' => $disk->lastModified($file),
                'modified_formatted' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                'name' => basename($file),
                'directory' => dirname($file),
            ];
        })->values();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $images = $images->filter(function ($image) use ($search) {
                return Str::contains(strtolower($image['name']), $search) ||
                       Str::contains(strtolower($image['path']), $search);
            });
        }

        // Apply directory filter
        if ($request->has('directory') && $request->directory !== 'all') {
            $directory = $request->directory;
            $images = $images->filter(function ($image) use ($directory) {
                return Str::startsWith($image['path'], $directory);
            });
        }

        // Sort by modified date (newest first)
        $images = $images->sortByDesc('modified')->values();

        // Get unique directories for filter
        $directories = collect($allFiles)
            ->map(fn($file) => dirname($file))
            ->unique()
            ->filter(fn($dir) => $dir !== '.')
            ->sort()
            ->values();

        // Statistics
        $stats = [
            'total_images' => $images->count(),
            'total_size' => $this->formatBytes($images->sum('size')),
            'directories_count' => $directories->count(),
        ];

        return view('admin.file-manager.index', compact('images', 'directories', 'stats'));
    }

    /**
     * Download a file
     */
    public function download($encodedPath)
    {
        $path = base64_decode($encodedPath);
        
        // Security: Validate path
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($path);
    }

    /**
     * Delete one or multiple files
     */
    public function delete(Request $request)
    {
        $request->validate([
            'paths' => 'required|array',
            'paths.*' => 'required|string',
        ]);

        $deleted = 0;
        $failed = [];

        foreach ($request->paths as $path) {
            // Security: Validate path
            if (Storage::disk('public')->exists($path)) {
                if (Storage::disk('public')->delete($path)) {
                    $deleted++;
                } else {
                    $failed[] = $path;
                }
            } else {
                $failed[] = $path;
            }
        }

        if (count($failed) > 0) {
            return back()->with('error', "Deleted {$deleted} files. Failed: " . implode(', ', $failed));
        }

        return back()->with('success', "Successfully deleted {$deleted} file(s).");
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
