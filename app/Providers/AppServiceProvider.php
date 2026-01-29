<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    public function boot()
{
    view()->composer('*', function ($view) {
        if (auth()->check()) {
            $view->with('unreadCount', \App\Models\Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count());
                
            $view->with('notifications', \App\Models\Notification::where('user_id', auth()->id())
                ->latest()
                ->get());
        }
    });
}
}
