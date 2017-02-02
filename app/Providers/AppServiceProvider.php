<?php

namespace App\Providers;
use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //add validation rule for phone numbers
        Validator::extend('phone', function($attribute, $value, $parameters)
        {
            return preg_match("/^[0]{1}[3]{1}[0-6]{1}[0-9]{1}[0-9]{7}$/", str_replace("-", "", $value));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
