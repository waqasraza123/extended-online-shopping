<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/**
 * model factory for shops
 */
$factory->define(App\Shop::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'shop_name' => $faker->company,
        'owner_name' => $faker->name,
        'email' => $faker->unique()->companyEmail,
        'phone' => $faker->phoneNumber,
        'telephone' => $faker->phoneNumber,
        'market_plaza' => $faker->city,
        'location' => $faker->streetAddress,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Mobile::class, function (Faker\Generator $faker) {
    return [
        'data_sku' => str_random(20),
        'title' => $faker->companySuffix,
        'brand' => $faker->company,
        'color' => $faker->colorName,
        'rating' => $faker->numberBetween(1, 5),
        'total_ratings' => $faker->numberBetween(1, 100),
        'old_price' => $faker->numberBetween(10000, 1000000),
        'current_price' => $faker->numberBetween(10000, 1000000),
        'discount' => $faker->numberBetween(1, 100),
        'local_online' => 'o',
        'shop_id' => function(){
            return factory(App\Shop::class)->create()->id;
        }
    ];
});

$factory->define(App\Laptop::class, function (Faker\Generator $faker) {
    return [
        'data_sku' => str_random(20),
        'title' => $faker->companySuffix,
        'brand' => $faker->company,
        'color' => $faker->colorName,
        'rating' => $faker->numberBetween(1, 5),
        'total_ratings' => $faker->numberBetween(1, 100),
        'old_price' => $faker->numberBetween(10000, 1000000),
        'current_price' => $faker->numberBetween(10000, 1000000),
        'discount' => $faker->numberBetween(1, 100),
        'local_online' => 'o',
        'shop_id' => function(){
            return factory(App\Shop::class)->create()->id;
        }
    ];
});
