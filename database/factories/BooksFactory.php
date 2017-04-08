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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Book::class, function (Faker\Generator $faker) {

  $title = array(
      0 => 'Absalom, Absalom!',
      1 => 'After Many a Summer Dies the Swan',
      2 => 'Ah, Wilderness!',
      3 => 'All Passion Spent',
      4 => 'An Acceptable Time',
      5 => 'An Evil Cradling',
      6 => 'Arms and the Man',
      7 => 'As I Lay Dying',
      8 => 'A Time to Kill',
      9 => 'Behold the Man',
      10 => 'Beneath the Bleeding',
      11 => 'Beyond the Mexique Bay',
      12 => 'Blithe Spirit',
      13 => 'Blue Remembered Earth',
      14 => 'Bury My Heart at Wounded Knee',
      15 => 'Butter In a Lordly Dish',
      16 => 'By Grand Central Station I Sat Down and Wept',
      17 => 'Cabbages and Kings',
      18 => 'Carrion Comfort',
      19 => 'A Catskill Eagle',
      20 => 'The Children of Men',
      21 => 'Clouds of Witness',
      22 => 'A Confederacy of Dunces',
      23 => 'Consider Phlebas',
      24 => 'Consider the Lilies',
      25 => 'Cover Her Face',
      26 => 'The Cricket on the Hearth',
      27 => 'The Curious Incident of the Dog in the Night-Time',
      28 => 'Dance Dance Dance',
      29 => 'A Darkling Plain',
      30 => 'Death Be Not Proud',
      31 => 'The Doors of Perception',
      32 => 'Down to a Sunless Sea',
      33 => 'Dulce et Decorum Est',
      34 => 'Dying of the Light',
      35 => 'East of Eden',
      36 => 'Ego Dominus Tuus',
      37 => 'Endless Night',
      38 => 'Everything is Illuminated',
      39 => 'Eyeless in Gaza',
      40 => 'Fair Stood the Wind for France',
      41 => 'Fame Is the Spur',
      42 => 'Far From the Madding Crowd',
      43 => 'Fear and Trembling',
      44 => 'For a Breath I Tarry',
      45 => 'For Whom the Bell Tolls',
      46 => 'From Here to Eternity',
      47 => 'A Glass of Blessings',
      48 => 'The Glory and the Dream',
      49 => 'The Golden Apples of the Sun',
      50 => 'The Golden Bowl'
    );

  $genre = array(
      0 => 'Horror',
      1 => 'Romance',
      2 => 'Thriller'
    );

  $section = array(
      0 => 'Circulation',
      1 => 'Periodical Section',
      2 => 'General Reference',
      3 => 'Childrens Section',
      4 => 'Fiction'
    );


    return [
        'title' => $title[$faker->numberBetween(0,50)],
        'author' => $faker->name,
        'genre' => $genre[$faker->numberBetween(0,2)],
        'section' => $section[$faker->numberBetween(0,4)],
        'status' => ''
    ];
});
