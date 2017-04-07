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

	/*
	$faker->bookTitle
	$faker->bookGenre
	$faker->bookSection
	i made a custom provider for these properties.
	see at vendor/fzaninotto/faker/src/Faker/Provider/Book.php
	*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Book::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->bookTitle($faker->numberBetween(0,50)),
        'author' => $faker->name,
        'genre' => $faker->bookGenre($faker->numberBetween(0,2)),
        'section' => $faker->bookSection($faker->numberBetween(0,4)),
    ];
});
