<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ExamTypeTableSeeder::class);
        $this->call(GeneralSettingsTableSeeder::class);
        $this->call(QuestionerQuestionsTableSeeder::class);
        $this->call(YoutubeTableSeeder::class);
    }
}
