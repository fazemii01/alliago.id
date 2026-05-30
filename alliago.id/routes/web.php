<?php

use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientApplicationController;
use App\Http\Controllers\ClientDocumentController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientMessageController;
use App\Http\Controllers\FlightTicketController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VisaCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function (string $locale) {
    $supported = ['id', 'en'];
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
    }
    return redirect()->back()->withHeaders(['Vary' => 'Accept-Language']);
})->name('lang.switch');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', LandingPageController::class);

Route::get('/admin-dashboard', AdminDashboardController::class)->middleware('auth')->name('admin.dashboard.view');

Route::redirect('/login', '/client/login')->name('login');

Route::get('/visa', [VisaCatalogController::class, 'index'])->name('visa.index');
Route::get('/visa/{slug}', [VisaCatalogController::class, 'show'])->name('visa.show');
Route::match(['get', 'post'], '/flights', [FlightTicketController::class, 'index'])->name('flights.index');
Route::get('/api/flights/airports', [FlightTicketController::class, 'searchAirports'])->name('flights.airports.search');
Route::get('/flights/airline-logo/{iata}', [FlightTicketController::class, 'airlineLogo'])->name('flights.airline_logo');

Route::get('/detail', [\App\Http\Controllers\PageController::class, 'detail'])->name('pages.detail');
Route::get('/proses', [\App\Http\Controllers\PageController::class, 'process'])->name('pages.process');
Route::get('/faq', [\App\Http\Controllers\PageController::class, 'faq'])->name('pages.faq');
Route::get('/refund-policy', [\App\Http\Controllers\PageController::class, 'refundPolicy'])->name('pages.refund_policy');
Route::get('/privacy-policy', [\App\Http\Controllers\PageController::class, 'privacyPolicy'])->name('pages.privacy_policy');

Route::middleware('guest')->group(function () {
    Route::get('/client/login', [ClientAuthController::class, 'showLogin'])->name('client.login');
    Route::post('/client/login', [ClientAuthController::class, 'login'])->name('client.login.store');
    Route::get('/client/register', [ClientAuthController::class, 'showRegister'])->name('client.register');
    Route::post('/client/register', [ClientAuthController::class, 'register'])->name('client.register.store');
});

Route::get('/client/applications/{application}/invoice', [\App\Http\Controllers\ClientInvoiceController::class, 'show'])->name('client.applications.invoice');

Route::middleware('auth')->group(function () {
    Route::get('/client/dashboard', ClientDashboardController::class)->name('client.dashboard');
    Route::get('/client/applications/{application}', [ClientApplicationController::class, 'show'])->name('client.applications.show');
    Route::get('/client/applications/create/{visaProduct:slug}', [ClientApplicationController::class, 'create'])->name('client.applications.create');
    Route::post('/client/applications/{visaProduct:slug}', [ClientApplicationController::class, 'store'])->name('client.applications.store');
    Route::post('/client/applications/{application}/documents/{document}', [ClientDocumentController::class, 'store'])->name('client.documents.store');
    Route::post('/client/applications/{application}/messages', [ClientMessageController::class, 'store'])->name('client.messages.store');
    Route::get('/client/applications/{application}/checkout', [\App\Http\Controllers\ClientCheckoutController::class, 'show'])->name('client.applications.checkout');
    Route::post('/client/applications/{application}/checkout', [\App\Http\Controllers\ClientCheckoutController::class, 'store'])->name('client.applications.checkout.store');
    Route::get('/client/profile', [\App\Http\Controllers\ClientProfileController::class, 'edit'])->name('client.profile.edit');
    Route::patch('/client/profile', [\App\Http\Controllers\ClientProfileController::class, 'update'])->name('client.profile.update');
    Route::put('/client/password', [\App\Http\Controllers\ClientProfileController::class, 'updatePassword'])->name('client.password.update');
    
    Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
});

Route::post('/webhooks/xendit', [\App\Http\Controllers\XenditWebhookController::class, 'handle'])->name('webhooks.xendit');
