<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $placeholderImage;
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


        Validator::extend('discount', function($attribute, $value, $parameters)
        {
            //current price (parameters) should obviously be greater than discount price
            return $parameters > $value;
        });

        $this->placeholderImage = url('/').'/uploads/placeholder.png';
        View::share('placeholderImage', $this->placeholderImage);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        # ADD this to the register method
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
    }
}
