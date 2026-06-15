<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // WORKAROUND: Force register DatabaseServiceProvider
        // This fixes "Target class [db] does not exist" error
        // when DatabaseServiceProvider doesn't load automatically
        if (!$this->app->bound('db')) {
            $this->app->register(\Illuminate\Database\DatabaseServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
