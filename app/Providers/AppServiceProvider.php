<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Adding Route Binding (Important)
        Route::bind('quotation', function (string $value) {
            return me()->currentTeam()->quotations()->where('id', $value)->firstOrFail();
        });
        Route::bind('invoices', function (string $value) {
            return me()->currentTeam()->invoices()->where('id', $value)->firstOrFail();
        });
        Route::bind('service', function (string $value) {
            return me()->currentTeam()->services()->where('id', $value)->firstOrFail();
        });
        Route::bind('tax', function (string $value) {
            return me()->currentTeam()->taxes()->where('id', $value)->firstOrFail();
        });
        Route::bind('template-services', function (string $value) {
            return me()->currentTeam()->templateServices()->where('id', $value)->firstOrFail();
        });
    }
}
