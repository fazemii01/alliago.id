<?php

namespace App\Providers;

use App\Contracts\FileStorage;
use App\Services\FileStorageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the central FileStorage contract to its single-disk MinIO
        // implementation as a singleton so every Upload_Handler and
        // URL_Resolver shares one instance via constructor injection.
        $this->app->singleton(FileStorage::class, FileStorageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
