<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Book;

class BookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	/*
        DB::table('books')->insert([
        	'title' => str_random(10),
        	'author' => str_random(10),
        	'genre' => str_random(10),
        	'section' => str_random(10)
        ]);    
        */

        factory(App\Book::class,20)->create();

    }
}
