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
        'email_phone' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
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
        'phone' => $faker->phoneNumber,
        'market_plaza' => $faker->streetName,
        'location' => $faker->streetAddress,
        'user_id' => function(){
            return factory(App\User::class)->create()->id;
        },
        'city' => $faker->city,
    ];
});

$mobile = $factory->define(App\Mobile::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->companySuffix,
        'image' => 'http://localhost:8000/uploads/products/mobiles/iphone_5_beverly_hills.jpg',
        'brand_id' => function(){
            return \App\Brand::first()->id;
        },
        'old_price' => $faker->numberBetween(10000, 1000000),
        'current_price' => $faker->numberBetween(1000, 100000),
        'discount' => $faker->numberBetween(1, 100).'%',
        'local_online' => 'l',
        'shop_id' => function(){
            return factory(App\Shop::class)->create()->id;
        },
        'stock' => $faker->numberBetween(1, 20),
        'link' => 'http:8000/products/mobiles'
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
