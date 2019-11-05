<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YoutubeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('youtube')->insert([
            'id' => 1,
            'profile_url' => 'https://www.youtube.com/embed/TvYcs9g2RUo',
            'buy_stel_url' => 'https://www.youtube.com/embed/KMFCqbl9SFQ',
            'qa_url' => 'https://www.youtube.com/embed/4sL5-d9yxl8',
            'ta_url' => 'https://www.youtube.com/embed/Ju-uU2kJ3m8',
            'vt_url' => 'https://www.youtube.com/embed/uGxUzfekYIE',
            'playlist_url' => 'https://www.youtube.com/embed?list=PLl3Z5rVQaSyXdmOjIJ2pKhBAIAEOno63C',

            'created_by' => '1',
            'updated_by' => '1',
            'created_at' => '2019-11-05 07:55:05',
            'updated_at' => '2019-11-05 07:55:05',
        ]);
    }
}
