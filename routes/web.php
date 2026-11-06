<?php

use App\Http\Controllers\BulletinController;
use App\Http\Controllers\MetadataScraperController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to bulletins or login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('bulletins.index');
    }
    return redirect()->route('login');
});

// Public bulletin view for sharing
Route::get('/folder/{slug}', [BulletinController::class, 'publicView'])->name('bulletins.public');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('bulletins.index');
    })->name('dashboard');

    // Bulletins
    Route::post('/bulletins/seed-demo', [BulletinController::class, 'seedDemo'])->name('bulletins.seed-demo');
    Route::post('/bulletins/{bulletin}/duplicate', [BulletinController::class, 'duplicate'])->name('bulletins.duplicate');
    Route::get('/bulletins/{bulletin}/render', [BulletinController::class, 'renderOnly'])->name('bulletins.render');
    Route::resource('bulletins', BulletinController::class);

    // Metadata Scraper & Image Upload API
    Route::post('/api/scraper/scrape', [MetadataScraperController::class, 'scrape'])->name('scraper.scrape');
    Route::post('/api/scraper/upload-image', [MetadataScraperController::class, 'uploadImage'])->name('scraper.upload-image');
    Route::get('/img-proxy', [MetadataScraperController::class, 'proxyImage'])->name('image.proxy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
