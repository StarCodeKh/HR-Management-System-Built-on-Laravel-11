<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;


class AppServiceProvider extends ServiceProvider
{
  

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    User::observe(UserObserver::class);
}
    
}
