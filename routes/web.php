<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

// Features Controllers
use App\Http\Controllers\Features\BatchController;
use App\Http\Controllers\Features\BeautifierController;
use App\Http\Controllers\Features\VirtualModelController;
use App\Http\Controllers\Features\ProductsVirtualController;

// Storage Controllers
use App\Http\Controllers\Storage\PromptController;
use App\Http\Controllers\Storage\ImageController;

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
    Route::get('/virtual-model', [VirtualModelController::class, 'index'])->name('virtual-model.index');
    
    // Products Virtual Feature
    Route::prefix('products-virtual')->name('products-virtual.')->group(function () {
        Route::get('/', [ProductsVirtualController::class, 'index'])->name('index');
        Route::post('/analyze', [ProductsVirtualController::class, 'analyze'])->name('analyze');
        Route::post('/generate', [ProductsVirtualController::class, 'generate'])->name('generate');
        Route::get('/{id}/status', [ProductsVirtualController::class, 'status'])->name('status');
        Route::post('/{id}/save-to-library', [ProductsVirtualController::class, 'saveToLibrary'])->name('save-to-library');
        Route::get('/{id}/download', [ProductsVirtualController::class, 'download'])->name('download');
    });
});

/*
|--------------------------------------------------------------------------
| Storage Hub Group (prefix: /storage)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('storage')->name('storage.')->group(function () {
    Route::post('prompts/generate', [PromptController::class, 'generate'])->name('prompts.generate');
    Route::post('prompts/{prompt}/duplicate', [PromptController::class, 'duplicate'])->name('prompts.duplicate');
    Route::post('prompts/{prompt}/toggle-favorite', [PromptController::class, 'toggleFavorite'])->name('prompts.toggle-favorite');
    Route::resource('prompts', PromptController::class);
    Route::resource('gallery', ImageController::class)->names([
        'index' => 'images.index',
        'create' => 'images.create',
        'store' => 'images.store',
        'show' => 'images.show',
        'edit' => 'images.edit',
        'update' => 'images.update',
        'destroy' => 'images.destroy',
    ]);
    // Model Presets (Public Read, Admin Write via Admin Group)
    Route::get('model-presets', [App\Http\Controllers\Admin\ModelPresetController::class, 'index'])->name('model-presets.index');
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
    Route::post('/settings/clear-temp', [SettingsController::class, 'clearProductsVirtualTemp'])->name('settings.clear-temp');
    
    // Wizard Options
    Route::resource('wizard-options', WizardOptionController::class);
    
    // Model Presets (Admin Full Access)
    Route::resource('model-presets', ModelPresetController::class)->except(['index']);
});

require __DIR__.'/auth.php';
