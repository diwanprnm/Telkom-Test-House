<?php

use Illuminate\Database\Seeder;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('articles')->insert([
            'id' => '1',
            'title' => 'About Us',
            'type' => 'About',
            'description' => '<p class="img-responsive center-block wow fadeInUp" data-wow-delay=".3s">PT. Infomedia Nusantara (Infomedia) is a subsidiary company of PT. Telekomunikasi Indonesia Tbk. (TELKOM), the largest telecommunication provider in Indonesia with business portfolios: TIMES (Telecommunication, Information, Media, Edutainment, Services). By direction of Telkom, Infomedia is focus on handling the portfolio of Information service to become a leader in Business Process Management company in the region.</p>',
            'is_active' => true,
            'created_by' => 'admin',
            'updated_by' => 'admin',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('articles')->insert([
            'id' => '2',
            'title' => 'Persyaratan Uji dan Sertifikasi',
            'type' => 'Persyaratan Uji dan Sertifikasi',
            'description' => '',
            'is_active' => true,
            'created_by' => 'admin',
            'updated_by' => 'admin',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
