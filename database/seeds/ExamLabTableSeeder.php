<?php

use Illuminate\Database\Seeder;

class ExamLabTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('examination_labs')->insert([
            ['id' => 'ca3b0d4f-803c-411a-811d-8fba8609b96b', 'name' => 'Lab CPE', 'description' => 'Lab CPE', 'is_active' => '1', 'created_by'	=> '1', 'updated_by' => '1', 'created_at' => '2016-11-07 23:30:43', 'updated_at' =>	'2020-10-12 18:18:07', 'lab_code' => '021', 'lab_init' => 'CPE', 'close_until' => '1900-01-01', 'open_at' => '2020-11-02'],
            ['id' => '5f87131a-a618-403a-8a88-f1a9ec06b2f1', 'name' => 'Lab Kabel', 'description' => 'Lab Kabel', 'is_active' => '1', 'created_by'	=> '1', 'updated_by' => '1', 'created_at' => '2016-11-07 23:30:43', 'updated_at' =>	'2020-10-12 18:18:07', 'lab_code' => '022', 'lab_init' => 'KAB', 'close_until' => '1900-01-01', 'open_at' => '2020-11-02'],
            ['id' => '8b931d0b-417e-4569-bb4e-1e39ba208962', 'name' => 'Lab Transmisi', 'description' => 'Lab Transmisi', 'is_active' => '1', 'created_by'	=> '1', 'updated_by' => '1', 'created_at' => '2016-11-07 23:30:43', 'updated_at' =>	'2020-10-12 18:18:07', 'lab_code' => '023', 'lab_init' => 'TRA', 'close_until' => '1900-01-01', 'open_at' => '2020-11-02'],
            ['id' => '666bfdf5-3315-4b9e-b680-3dc23cf000f9', 'name' => 'Lab Energi', 'description' => 'Lab Energi', 'is_active' => '1', 'created_by'	=> '1', 'updated_by' => '1', 'created_at' => '2016-11-07 23:30:43', 'updated_at' =>	'2020-10-12 18:18:07', 'lab_code' => '024', 'lab_init' => 'ENE', 'close_until' => '1900-01-01', 'open_at' => '2020-11-02'],
            ['id' => 'eec71263-9dd1-443b-9aeb-11605933fed9', 'name' => 'Lab Kalibrasi', 'description' => 'KAL', 'is_active' => '1', 'created_by'	=> '1', 'updated_by' => '1', 'created_at' => '2016-11-07 23:30:43', 'updated_at' =>	'2020-10-12 18:18:07', 'lab_code' => '025', 'lab_init' => 'Lab. Kalib', 'close_until' => '1900-01-01', 'open_at' => '2020-11-02'],
            ['id' => '9f4cf85f-ba96-4a1d-969e-b438ebe1172b', 'name' => 'Lab EMC', 'description' => 'Lab EMC', 'is_active' => '1', 'created_by'	=> '1', 'updated_by' => '1', 'created_at' => '2016-11-07 23:30:43', 'updated_at' =>	'2020-10-12 18:18:07', 'lab_code' => '026', 'lab_init' => 'Lab EMC', 'close_until' => '1900-01-01', 'open_at' => '2020-11-02']
        ]);
    }
}
