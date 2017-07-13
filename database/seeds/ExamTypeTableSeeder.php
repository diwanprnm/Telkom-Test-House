<?php

use Illuminate\Database\Seeder;

class ExamTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('examination_types')->insert([
            ['id' => '1', 'name' => 'QA', 'description' => 'Quality Assurance', 'created_at' => date("Y-m-d H:i:s"), 'picture' => 'LabCPE.jpg'],
            ['id' => '2', 'name' => 'TA', 'description' => 'Type Approval', 'created_at' => date("Y-m-d H:i:s"), 'picture' => 'LabEnergi.jpg'],
            ['id' => '3', 'name' => 'VT', 'description' => 'Voluntary Test', 'created_at' => date("Y-m-d H:i:s"), 'picture' => 'LabKabel.jpg'],
            ['id' => '4', 'name' => 'CAL', 'description' => 'Calibration', 'created_at' => date("Y-m-d H:i:s"), 'picture' => 'LabTransmisi.jpg']
        ]);
    }
}
