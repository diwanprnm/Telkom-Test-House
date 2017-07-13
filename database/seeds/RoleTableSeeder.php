<?php

use Illuminate\Database\Seeder;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => '1', 'name' => 'admin', 'created_at' => date("Y-m-d H:i:s")],
            ['id' => '2', 'name' => 'user', 'created_at' => date("Y-m-d H:i:s")]
        ]);
    }
}
