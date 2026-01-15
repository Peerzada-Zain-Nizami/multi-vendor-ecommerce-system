<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SmsaSDK\Smsa;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Smsa::key(env('SMSA_PASSKEY'));
    }
}
