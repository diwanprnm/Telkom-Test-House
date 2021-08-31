<?php

use Illuminate\Database\Seeder;

class GeneralSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('general_settings')->truncate();

        DB::table('general_settings')->insert([
            'code' => 'manager_urel',
            'value' => 'Sontang Hutapea',
            'is_active' => '1',
            'created_by' => '1',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_settings')->insert([
			'code' => 'poh_manager_urel',
			'value' => 'Yudha Indah',
			'created_by' => '1',
			'created_at' => date("Y-m-d H:i:s"),
		]);
        DB::table('general_settings')->insert([
			'code' => 'send_email',
			'value' => 'Send Email',
			'created_by' => '1',
			'created_at' => date("Y-m-d H:i:s"),
		]);
        DB::table('general_settings')->insert([
            'code' => 'sm_urel',
            'value' => 'I Gede Astawa',
            'is_active' => '1',
            'created_by' => '1',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_settings')->insert([
			'code' => 'poh_sm_urel',
			'value' => 'Adi Permadi',
			'created_by' => '1',
			'created_at' => date("Y-m-d H:i:s"),
		]);
    }
}
