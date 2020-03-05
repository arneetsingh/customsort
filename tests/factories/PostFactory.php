<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Arni\CustomSort\Tests\Models\Post;
use Faker\Generator as Faker;

//$factory = new \Illuminate\Database\Eloquent\Factory;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->word
    ];
});
