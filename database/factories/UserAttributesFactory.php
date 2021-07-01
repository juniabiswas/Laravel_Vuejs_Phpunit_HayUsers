<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\User;
use App\UserAttributes;
use Faker\Generator as Faker;

$factory->define(App\UserAttributes::class, function (Faker $faker) {
$user_id = User::all()->pluck('id')->toArray();
    
    
    return [

       'gender'=>'female',
        'user_id' => 1,//$faker->randomElement->$user_id,
        'date_of_birth' => $faker->dateTimeBetween('1970-01-01', '2012-12-31'),
        
    ];
});





