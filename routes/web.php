<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

// Features Controllers
use App\Http\Controllers\Features\BatchController;
use App\Http\Controllers\Features\BeautifierController;
use App\Http\Controllers\Features\StagingController;
use App\Http\Controllers\Features\VirtualModelController;
use App\Http\Controllers\Features\WizardController;

// Storage Controllers
use App\Http\Controllers\Storage\ProductController;
use App\Http\Controllers\Storage\PromptController;
use App\Http\Controllers\Storage\ImageController;
use App\Http\Controllers\Storage\ModelController;

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\WizardOptionController;
use App\Http\Controllers\Admin\ModelPresetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Features Group (prefix: /features)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('features')->name('features.')->group(function () {
    Route::get('/batch', [BatchController::class, 'index'])->name('batch.index');
    Route::get('/beautifier', [BeautifierController::class, 'index'])->name('beautifier.index');
    Route::get('/staging', [StagingController::class, 'index'])->name('staging.index');
    Route::get('/virtual-model', [VirtualModelController::class, 'index'])->name('virtual-model.index');
    Route::get('/wizard', [WizardController::class, 'index'])->name('wizard.index');
});

/*
|--------------------------------------------------------------------------
| Storage Hub Group (prefix: /storage)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('storage')->name('storage.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('prompts', PromptController::class);
    Route::resource('images', ImageController::class);
    Route::resource('models', ModelController::class);
});

/*
|--------------------------------------------------------------------------
| History
|--------------------------------------------------------------------------
*/
Route::get('/history', [HistoryController::class, 'index'])->middleware('auth')->name('history.index');

/*
|--------------------------------------------------------------------------
| Admin Group (prefix: /admin, role: Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-lock', [UserController::class, 'toggleLock'])->name('users.toggle-lock');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // Wizard Options
    Route::resource('wizard-options', WizardOptionController::class);
    
    // Model Presets
    Route::resource('model-presets', ModelPresetController::class);
});

require __DIR__.'/auth.php';
