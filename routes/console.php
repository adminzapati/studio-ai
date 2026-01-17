<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

// Scheduled Task: Clean up temp files (Daily)
Schedule::call(function () {
    $path = 'temp/products-virtual';
    
    if (Storage::disk('public')->exists($path)) {
        $files = Storage::disk('public')->allFiles($path);
        $count = 0;
        $now = now();
        
        foreach ($files as $file) {
            // Check if file is older than 24 hours
            $lastModified = Storage::disk('public')->lastModified($file);
            if ($now->diffInHours(\Carbon\Carbon::createFromTimestamp($lastModified)) >= 24) {
                Storage::disk('public')->delete($file);
                $count++;
            }
        }
        
        if ($count > 0) {
            \App\Core\Logging\AppLogger::info("Scheduled Cleanup: Deleted {$count} old temp files.");
        }
    }
})->name('cleanup:products-virtual-temp')->cron(match (\App\Models\Setting::get('cleanup_schedule', 'daily')) {
    'hourly' => '0 * * * *',
    'weekly' => '0 0 * * 0',
    'monthly' => '0 0 1 * *',
    default => '0 0 * * *', // daily
});
