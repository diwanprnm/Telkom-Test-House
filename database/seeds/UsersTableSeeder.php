<?php

use Illuminate\Database\Seeder;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
			'id' => Uuid::uuid4(),
			'role_id' => '1',
			'company_id' => '1',
			'name' => 'admin',
			'email' => 'admin@mail.com',
			'password' => bcrypt('admin'),
			'is_active' => true,
			'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
    }
}
