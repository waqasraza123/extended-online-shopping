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
            if(preg_match("/^[0]{1}[3]{1}[0-6]{1}[0-9]{1}-[0-9]{7}$/", $value)){
                return true;
            }

            if(preg_match('/^[0]{1}[0-9]{2}-[0-9]{7}$/', $value)){
                return true;
            }

            return false;
        });


        Validator::extend('discount', function($attribute, $value, $parameters)
        {
            //parameters[0] contain old price
            //value contains current_price
            if($parameters[0] == '0'){
                return true;
            }
            else{
                return $parameters[0] > $value;
            }
        });

        Validator::extend('eos_int', function ($attribute, $value, $parameters){
            return (int)$value >= 0 ? true : false;
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
