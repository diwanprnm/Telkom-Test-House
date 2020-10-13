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

        DB::table('admin_roles')->insert([
			'user_id' => '1',
			'registration_status' => '1',
            'function_status' => '1',
            'contract_status' => '1',
            'spb_status' => '1',
            'payment_status' => '1',
            'spk_status' => '1',
            'examination_status' => '1',
            'resume_status' => '1',
            'qa_status' => '1',
            'certificate_status' => '1',
            'equipment_status' => '1',
            'receipt_status' => '1',
			'user_name' => 'admin',
            'user_email' => 'admin@mail.com',
            'created_by' => '1',
            'updated_by' => '1',
			'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
    }
}