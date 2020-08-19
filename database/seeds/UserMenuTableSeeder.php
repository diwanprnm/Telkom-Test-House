<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
// use File
// use Illuminate\Support\Facades\DB

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UserMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::unprepared(\File::get(base_path('database/seeds/UserMenuSeed.sql')));
        \DB::update('update users set id = 1 where email = ?', ['admin@mail.com']);
    }
}