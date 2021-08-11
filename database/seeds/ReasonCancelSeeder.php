<?php

use Illuminate\Database\Seeder;

class ReasonCancelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reason_cancels')->insert([
            ['id' => '1', 'name' => 'Referensi uji perangkat tidak berlaku lagi.', 'from_admin' => '1', 'is_active' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), 'created_by' => "1", 'updated_by' => "1"],
            ['id' => '2', 'name' => 'Ada alat ukur pengujian rusak.', 'from_admin' => '1', 'is_active' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), 'created_by' => "1", 'updated_by' => "1"],
            ['id' => '3', 'name' => 'Kendala pada  perangkat saya.', 'from_admin' => '1', 'is_active' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), 'created_by' => "1", 'updated_by' => "1"],
            ['id' => '4', 'name' => 'Tidak jadi ikut tender Telkom.', 'from_admin' => '1', 'is_active' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), 'created_by' => "1", 'updated_by' => "1"],
            ['id' => '5', 'name' => 'Perangkat akan diuji di tempat lain.', 'from_admin' => '1', 'is_active' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), 'created_by' => "1", 'updated_by' => "1"]
        ]);
    }
}
