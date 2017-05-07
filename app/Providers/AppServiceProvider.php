<?php

namespace App\Providers;
use App\Observers\UserObserver;
use App\Observers\UserVerificationObserver;
use App\User;
use App\UserVerification;
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
            if(preg_match("/^[0]{1}[3]{1}[0-6]{1}[0-9]{1}[0-9]{7}$/", str_replace("-", "", $value))){
                return true;
            }

            if(preg_match('/^[0]{1}[0-9]{9}$/', str_replace("-", "", $value))){
                return true;
            }

            return false;
        });


        Validator::extend('discount', function($attribute, $value, $parameters)
        {
            //current price (parameters) is discounted price
            //so should obviously be less than old price
            return $parameters > $value;
        });

        $this->placeholderImage = url('/').'/uploads/placeholder.png';
        View::share(
            [
                'placeholderImage' => $this->placeholderImage
            ]
        );

        //add observer class for users
        //for firing email sending events
        //for email verifications
        User::observe(UserObserver::class);
        UserVerification::observe(UserVerificationObserver::class);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
