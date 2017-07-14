<?php

use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->insert([
            ['id' => '3', 'title' => 'x-banner-1.png', 'type' => 'Procedure', 'description' => 'Untuk info lebih lanjut, silakan hubungi : ', 'is_active' => '1', 'created_at' => date("Y-m-d H:i:s")],
            ['id' => '4', 'title' => 'x-banner-2.png', 'type' => 'Procedure', 'description' => 'Untuk info lebih lanjut, silakan hubungi : ', 'is_active' => '1', 'created_at' => date("Y-m-d H:i:s")]
        ]);
    }
}
