<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Post;
use App\Observers\UserObserver;
use App\Observers\PostObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // // Set a default string length for database schemas
        // Schema::defaultStringLength(191);

        // // Force HTTPS in production
        // if($this->app->environment('production')) {
        //     URL::forceScheme('https');
        // }

        // // Register observers for User and Post models
        // User::observe(UserObserver::class);
        // Post::observe(PostObserver::class);
    }
}