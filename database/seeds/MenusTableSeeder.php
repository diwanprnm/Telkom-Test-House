<?php

use Illuminate\Database\Seeder;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('menus')->insert([
            ['id' => '1', 'parent_id' => 0, 'name' => 'Beranda', 'url' => '', 'icon' => 'ti-dashboard'],
            ['id' => '2', 'parent_id' => 0, 'name' => 'Pengujian', 'url' => '#', 'icon' => 'ti-files'],
            ['id' => '3', 'parent_id' => 2, 'name' => 'Pengujian', 'url' => 'examination', 'icon' => null],
            ['id' => '4', 'parent_id' => 2, 'name' => 'Pengujian Saya', 'url' => 'myexam', 'icon' => null],
            ['id' => '5', 'parent_id' => 2, 'name' => 'Riwayat Pengujian Selesai', 'url' => 'examinationdone', 'icon' => null],
            ['id' => '6', 'parent_id' => 2, 'name' => 'Perangkat Lulus Uji', 'url' => 'device', 'icon' => null],
            ['id' => '7', 'parent_id' => 2, 'name' => 'Equipment', 'url' => 'equipment', 'icon' => null],
            ['id' => '8', 'parent_id' => 0, 'name' => 'Dashboard', 'url' => 'topdashboard', 'icon' => 'ti-target'],
            ['id' => '9', 'parent_id' => 0, 'name' => 'Customer Relation', 'url' => '#', 'icon' => 'ti-comments-smiley'],
            ['id' => '10', 'parent_id' => 9, 'name' => 'Feedback', 'url' => 'feedback', 'icon' => 'ti-comments'],
            ['id' => '11', 'parent_id' => 9, 'name' => 'Testimonial', 'url' => 'testimonial', 'icon' => 'ti-comment-alt'],
            ['id' => '12', 'parent_id' => 0, 'name' => 'Data Master', 'url' => '#', 'icon' => 'ti-server'],
            ['id' => '13', 'parent_id' => 12, 'name' => 'Artikel', 'url' => 'article', 'icon' => null],
            ['id' => '14', 'parent_id' => 12, 'name' => 'STEL/STD', 'url' => 'stel', 'icon' => null],
            ['id' => '15', 'parent_id' => 12, 'name' => 'Tarif Pengujian', 'url' => 'charge', 'icon' => null],
            ['id' => '16', 'parent_id' => 12, 'name' => 'Tarif Kalibrasi', 'url' => 'calibration', 'icon' => null],
            ['id' => '17', 'parent_id' => 12, 'name' => 'Slideshow', 'url' => 'slideshow', 'icon' => null],
            ['id' => '18', 'parent_id' => 12, 'name' => 'Lab Pengujian', 'url' => 'labs', 'icon' => null],
            ['id' => '19', 'parent_id' => 12, 'name' => 'Perusahaan', 'url' => 'company', 'icon' => null],
            ['id' => '20', 'parent_id' => 12, 'name' => 'Permohonan edit Perusahaan', 'url' => 'tempcompany', 'icon' => null],
            ['id' => '21', 'parent_id' => 12, 'name' => 'Partners', 'url' => 'footer', 'icon' => null],
            ['id' => '22', 'parent_id' => 0, 'name' => 'Tools', 'url' => '#', 'icon' => 'ti-settings'],
            ['id' => '23', 'parent_id' => 22, 'name' => 'Backup & Restore', 'url' => 'backup', 'icon' => null],
            ['id' => '24', 'parent_id' => 22, 'name' => 'Activity Log', 'url' => 'log', 'icon' => null],
            ['id' => '25', 'parent_id' => 0, 'name' => 'User & Role Management', 'url' => '#', 'icon' => 'ti-hand-open'],
            ['id' => '26', 'parent_id' => 25, 'name' => 'User Role', 'url' => 'role', 'icon' => null],
            ['id' => '27', 'parent_id' => 25, 'name' => 'User', 'url' => 'user', 'icon' => null],
            ['id' => '28', 'parent_id' => 25, 'name' => 'Role Pengujian', 'url' => 'privilege', 'icon' => null],
            ['id' => '29', 'parent_id' => 0, 'name' => 'Keuangan', 'url' => '#', 'icon' => 'ti-money'],
            ['id' => '30', 'parent_id' => 29, 'name' => 'Rekap Pembelian STEL', 'url' => 'sales', 'icon' => 'ti-money'],
            ['id' => '31', 'parent_id' => 29, 'name' => 'Rekap Pengujian Perangkat', 'url' => 'income', 'icon' => 'ti-money'],
            ['id' => '32', 'parent_id' => 0, 'name' => 'Web Statistic', 'url' => 'analytic', 'icon' => 'ti-pie-chart'],
        ]);

    }
}
