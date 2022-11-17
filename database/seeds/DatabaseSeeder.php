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
        $this->call(ExamLabTableSeeder::class);
        $this->call(GeneralSettingsTableSeeder::class);
        $this->call(QuestionerQuestionsTableSeeder::class);
        $this->call(YoutubeTableSeeder::class);
        $this->call(UserMenuTableSeeder::class);
        $this->call(EmailEditorSeeder::class);
        $this->call(FaqTableSeeder::class);
        $this->call(AutentikasiEditorTableSeeder::class);
    }
}
