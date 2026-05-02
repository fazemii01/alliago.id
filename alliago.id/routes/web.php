<?php

use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientApplicationController;
use App\Http\Controllers\ClientDocumentController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientMessageController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\VisaCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPageController::class);

Route::get('/admin-dashboard', AdminDashboardController::class)->middleware('auth')->name('admin.dashboard.view');

Route::redirect('/login', '/client/login')->name('login');

Route::get('/visa', [VisaCatalogController::class, 'index'])->name('visa.index');
Route::get('/visa/{slug}', [VisaCatalogController::class, 'show'])->name('visa.show');

Route::middleware('guest')->group(function () {
    Route::get('/client/login', [ClientAuthController::class, 'showLogin'])->name('client.login');
    Route::post('/client/login', [ClientAuthController::class, 'login'])->name('client.login.store');
    Route::get('/client/register', [ClientAuthController::class, 'showRegister'])->name('client.register');
    Route::post('/client/register', [ClientAuthController::class, 'register'])->name('client.register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/client/dashboard', ClientDashboardController::class)->name('client.dashboard');
    Route::get('/client/applications/{application}', [ClientApplicationController::class, 'show'])->name('client.applications.show');
    Route::get('/client/applications/create/{visaProduct:slug}', [ClientApplicationController::class, 'create'])->name('client.applications.create');
    Route::post('/client/applications/{visaProduct:slug}', [ClientApplicationController::class, 'store'])->name('client.applications.store');
    Route::post('/client/applications/{application}/documents/{document}', [ClientDocumentController::class, 'store'])->name('client.documents.store');
    Route::post('/client/applications/{application}/messages', [ClientMessageController::class, 'store'])->name('client.messages.store');
    Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
});
